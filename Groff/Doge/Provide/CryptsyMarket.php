<?php namespace Groff\Doge\Provide;


use Groff\Doge\Setting;
use \ORM;

class CryptsyMarket extends Cacheable implements MarketInterface
{
    protected $coin;

    private $urls = array(
        "buy" => "https://www.cryptsy.com/json.php?file=ajaxbuyorderslistv2_{cryptsy_id}.json",
        "sell" => "https://www.cryptsy.com/json.php?file=ajaxsellorderslistv2_{cryptsy_id}.json",
        "trades" => "https://www.cryptsy.com/json.php?file=ajaxtradehistory_{cryptsy_id}.json"
    );

    private $cacheTime = 4;

    /*
     *
        "trades_url"      : "",
        "sell_orders_url" : "",
        "buy_orders_url"  : "",
     */

    public function __construct($coin)
    {
        $this->coin = $coin;
    }

    public function last()
    {
        $url = $this->url("trades");
        return $this->fetchUrl($url);
    }

    public function orders()
    {
        // TODO: Implement orders() method.
    }

    public function price()
    {
        $url = $this->url("trades");
        $trades = $this->fetchUrl($url, 60);
        $first = array_shift($trades->aaData);
        $btc = $first[2];
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


        if(!$data["trades"]){
            $data["trades"] = false;
        }
        if(!$data["sells"]){
            $data["sells"] = false;
        }
        if(!$data["buys"]){
            $data["buys"] = false;
        }

        return json_encode($data);
    }

    protected function adjustData($data, $url){
        $isBuy  = $url === $this->url("buy");
        $isSell = $url === $this->url("sell");


        //$isRates = $url === Setting::get("api.rates_url");
        //$isReceivedValue  = strpos($url, Setting::get("api.address.received_url")) !== FALSE;


        if($isBuy || $isSell){
            $orderbookCount = Setting::get("api.orderbook_count");
            $data = json_decode($data);

            //something went wrong?
            if(empty($data->aaData)){
                return $data;
            }

            $data->aaData = array_slice($data->aaData, 0, $orderbookCount);
            $data = json_encode($data);
        }

//        if($isReceivedValue){

//        }
//
//        if($isRates){
//            $data = $this->adjustCoinbaseRates($data);
//        }

        return $data;
    }

    private function url($key)
    {
        $url = $this->urls[$key];
        return str_replace('{cryptsy_id}', $this->coin["cryptsy_id"], $url);
    }


}