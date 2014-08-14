<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2/27/14
 * Time: 7:08 PM
 */

namespace Groff\Doge\Provide;

use Groff\Doge\Market\CurrencyOverview;

class MintpalMarket extends Cacheable implements MarketInterface
{

    private $refreshTime = 10;

    private $coin;

    private $urls = array(
        "overview" => "https://api.mintpal.com/market/summary/BTC",
        "buys" => "https://api.mintpal.com/market/orders/{symbol}/BTC/BUY",
        "sells" => "https://api.mintpal.com/market/orders/{symbol}/BTC/SELL",
        "trades" => "https://api.mintpal.com/market/trades/{symbol}/BTC"
    );

    public function __construct($coin = false)
    {
        $this->coin = $coin;
    }

    public function overview()
    {
        $formattedData = array();
        $url = $this->urls["overview"];
        $result = $this->fetchUrl($url, 20);

        foreach ($result as $item) {
            $formattedData[$item->symbol] = new CurrencyOverview($item);
        }
        return $formattedData;
    }

    public function last()
    {
        // TODO: Implement last() method.
    }

    public function orders($request = "all")
    {
        if ($request === 'buy') {
            return $this->fetchUrl($this->url("buys"), 20);
        } elseif ($request === "sell") {
            return $this->fetchUrl($this->url("sells"), 20);
        }

        return array(
            "buy" => $this->fetchUrl($this->url("buys"), 20),
            "sell" => $this->fetchUrl($this->url("sells"), 20)
        );

    }

    public function all($time)
    {
        $data = array(
            "time" => time()
        );
        $data["trades"] = $this->fetchNewData($this->url("trades"), $time, $this->refreshTime);
        $data["sells"] = $this->fetchNewData($this->url("sells"), $time, $this->refreshTime);
        $data["buys"] = $this->fetchNewData($this->url("buys"), $time, $this->refreshTime);


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


    public function price()
    {

    }


    protected function adjustData($data, $url)
    {
        switch ($url) {
            case $this->url("overview"):
                return $this->adjustOverview($data);
                break;

            case $this->url("buys"):
            case $this->url("sells"):
                return $this->adjustOrderbook($data);
                break;
            case $this->url("trades"):
                return $this->adjustTrades($data);
                break;
        }
    }

    protected function adjustTrades($data)
    {
        $newData = array();
        $data = json_decode($data, true);

        foreach ($data["trades"] as $trade) {
            $newData[] = array(
                "type" => ($trade["type"] == "0" ? "Buy" : "Sell"),
                "price" => $trade["price"],
                "total" => $trade["total"],
                "amount" => $trade["amount"],
                "time" => (int)$trade["time"],
            );

        }

        return json_encode($newData);
    }

    protected function adjustOverview($data)
    {
        $newData = array();
        $data = json_decode($data, true);

        foreach ($data as $market) {
            $newData[] = array(
                "price" => $market["last_price"],
                "symbol" => $market["code"],
                "base" => $market["exchange"],
                "label" => $market["code"] . '/' . $market["exchange"],
                "market" => "mintpal",
            );

        }

        return json_encode($newData);
    }


    protected function adjustOrderbook($data)
    {
        $data = json_decode($data, true);
        $array = array();

        foreach($data["orders"] as $order)
        {
            $array[] = array(
                "price" => $order["price"],
                "count" => $order["amount"],
                "btc"   => $order["total"],
            );
        }

        $newData = array(
            "orders" => $array
        );

        return json_encode($newData);
    }

    private function url($key)
    {
        $url = $this->urls[$key];

        if (is_string($this->coin)) {
            $id = $this->coin;
        } else {
            $id = $this->coin["symbol"];
        }

        return str_replace('{symbol}', $id, $url);
    }

}