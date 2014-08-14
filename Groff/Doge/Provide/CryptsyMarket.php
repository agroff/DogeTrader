<?php namespace Groff\Doge\Provide;


use Groff\Doge\Setting;
use Groff\Doge\Market\CurrencyOverview;
use \ORM;

class CryptsyMarket extends Cacheable implements MarketInterface
{
    protected $coin;

    private $urls = array(
        "overview" => "http://pubapi.cryptsy.com/api.php?method=marketdatav2",
        "buy" => "https://www.cryptsy.com/json.php?file=ajaxbuyorderslistv2_{cryptsy_id}.json",
        "sell" => "https://www.cryptsy.com/json.php?file=ajaxsellorderslistv2_{cryptsy_id}.json",
        "trades" => "https://www.cryptsy.com/json.php?file=ajaxtradehistory_{cryptsy_id}.json"
    );

    private $cacheTime = 4;


    public function __construct($coin = FALSE)
    {
        $this->coin = $coin;
    }

    public function overview()
    {
        $formattedData = array();
        $url = $this->url("overview");
        $result = $this->fetchUrl($url, Setting::get("api.overview_cache"));

        foreach ($result as $item) {
            $formattedData[$item->symbol] = new CurrencyOverview($item);
        }
        return $formattedData;
    }

    public function last()
    {
        $url = $this->url("trades");
        return $this->fetchUrl($url);
    }

    public function orders($request = "all")
    {
        if ($request === 'buy') {
            return $this->fetchUrl($this->url("buy"));
        } elseif ($request === "sell") {
            return $this->fetchUrl($this->url("sell"));
        }

        return array(
            "buy" => $this->fetchUrl($this->url("buy")),
            "sell" => $this->fetchUrl($this->url("sell")),
        );

    }

    public function price()
    {
        $url = $this->url("trades");
        $trades = $this->fetchUrl($url, 60);
        $first = array_shift($trades);
        $btc = $first->price;
        list($zero, $satoshi) = explode('.', $btc);
        $satoshi = intval($satoshi);
        return $satoshi;
    }

    public function all($time)
    {
        $cacheTime = Setting::coin("market_cache");
        $data = array(
            "time" => time(),
            "trades" => $this->fetchNewData($this->url("trades"), $time),
            "sells" => $this->fetchNewData($this->url("sell"), $time),
            "buys" => $this->fetchNewData($this->url("buy"), $time),
        );


        if (!$data["trades"]) {
            $data["trades"] = false;
        }
        if (!$data["sells"]) {
            $data["sells"] = false;
        }
        if (!$data["buys"]) {
            $data["buys"] = false;
        }

        return json_encode($data);
    }

    protected function adjustData($data, $url)
    {
        $isBuy = $url === $this->url("buy");
        $isSell = $url === $this->url("sell");
        $isOverview = $url === $this->url("overview");
        $isTrade = $url === $this->url("trades");


        //$isRates = $url === Setting::get("api.rates_url");
        //$isReceivedValue  = strpos($url, Setting::get("api.address.received_url")) !== FALSE;


        if ($isBuy || $isSell) {
            $orderbookCount = Setting::get("api.orderbook_count");
            $data = json_decode($data);

            //something went wrong?
            if (empty($data->aaData)) {
                return $data;
            }

            $newData = new \stdClass();
            $newData->orders = array_slice($data->aaData, 0, $orderbookCount);

            foreach ($newData->orders as $key => $array) {
                $newData->orders[$key] = array(
                    "price" => $array[0],
                    "count" => $array[1],
                    "btc" => $array[2],
                );
            }

            $data = json_encode($newData);
        }

        if ($isOverview) {
            $data = $this->formatOverview($data);
        }

        if ($isTrade) {
            $data = $this->adjustTrades($data);
        }

        return $data;
    }

    protected function adjustTrades($data)
    {
        $newData = array();
        $data = json_decode($data, true);

        foreach ($data["aaData"] as $trade) {
            $newData[] = array(
                "type" => $trade[1],
                "price" => $trade[2],
                "total" => $trade[4],
                "amount" => $trade[3],
                "time" => strtotime($trade[0]) + (60 * 60 * 4),
            );

        }

        return json_encode($newData);
    }

    private function formatOverview($data)
    {
        $newData = array();
        $data = json_decode($data, true);

        foreach ($data["return"]["markets"] as $market) {
            if ($market["secondarycode"] !== 'BTC') {
                continue;
            }

            //dbg($market);

            //echo "  '$market[primarycode]' => '$market[marketid]',   <br />";

            $newData[] = array(
                "price" => $market["lasttradeprice"],
                "symbol" => $market["primarycode"],
                "base" => $market["secondarycode"],
                "label" => $market["label"],
                "market" => "cryptsy",
            );

        }

        return json_encode($newData);
    }

    private function url($key)
    {
        $url = $this->urls[$key];

        if (is_string($this->coin)) {
            $id = $this->symbolToId($this->coin);
        } else {
            $id = $this->coin["cryptsy_id"];
        }

        return str_replace('{cryptsy_id}', $id, $url);
    }


    private function symbolToId($symbol)
    {
        $lookup = array(
            '42' => '141',
            'ALF' => '57',
            'AMC' => '43',
            'ANC' => '66',
            'ARG' => '48',
            'AUR' => '160',
            'BCX' => '142',
            'BEN' => '157',
            'BET' => '129',
            'BQC' => '10',
            'BTB' => '23',
            'BTE' => '49',
            'BTG' => '50',
            'BUK' => '102',
            'CACH' => '154',
            'CAP' => '53',
            'CASH' => '150',
            'CAT' => '136',
            'CGB' => '70',
            'CLR' => '95',
            'CMC' => '74',
            'CNC' => '8',
            'CRC' => '58',
            'CSC' => '68',
            'DEM' => '131',
            'DGB' => '167',
            'DGC' => '26',
            'DMD' => '72',
            'DOGE' => '132',
            'DRK' => '155',
            'DVC' => '40',
            'EAC' => '139',
            'ELC' => '12',
            'EMD' => '69',
            'EZC' => '47',
            'FFC' => '138',
            'FLAP' => '165',
            'FRC' => '39',
            'FRK' => '33',
            'FST' => '44',
            'FTC' => '5',
            'GDC' => '82',
            'GLC' => '76',
            'GLD' => '30',
            'GLX' => '78',
            'HBN' => '80',
            'IFC' => '59',
            'IXC' => '38',
            'JKC' => '25',
            'KGC' => '65',
            'LEAF' => '148',
            'LK7' => '116',
            'LKY' => '34',
            'LOT' => '137',
            'LTC' => '3',
            'MAX' => '152',
            'MEC' => '45',
            'MEOW' => '149',
            'MINT' => '156',
            'MNC' => '7',
            'MZC' => '164',
            'NAN' => '64',
            'NBL' => '32',
            'NEC' => '90',
            'NET' => '134',
            'NMC' => '29',
            'NRB' => '54',
            'NVC' => '13',
            'NXT' => '159',
            'ORB' => '75',
            'OSC' => '144',
            'PHS' => '86',
            'Points' => '120',
            'PPC' => '28',
            'PTS' => '119',
            'PXC' => '31',
            'PYC' => '92',
            'QRK' => '71',
            'RDD' => '169',
            'RPC' => '143',
            'RYC' => '9',
            'SAT' => '168',
            'SBC' => '51',
            'SMC' => '158',
            'SPT' => '81',
            'SRC' => '88',
            'STR' => '83',
            'SXC' => '153',
            'TAG' => '117',
            'TAK' => '166',
            'TEK' => '114',
            'TGC' => '130',
            'TRC' => '27',
            'UNO' => '133',
            'UTC' => '163',
            'VTC' => '151',
            'WDC' => '14',
            'XJO' => '115',
            'XPM' => '63',
            'YAC' => '11',
            'YBC' => '73',
            'ZCC' => '140',
            'ZED' => '170',
            'ZET' => '85',
        );

        return $lookup[$symbol];
    }


}