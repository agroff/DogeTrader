<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2/27/14
 * Time: 7:08 PM
 */

namespace Groff\Doge\Provide;

use Groff\Doge\Market\CurrencyOverview;

class PoloniexMarket extends Cacheable implements MarketInterface{

    private $coin;

    private $refreshTime = 10;

    private $urls = array(
        "overview" => "https://poloniex.com/public?command=returnTicker",
        "orders" => "https://poloniex.com/public?command=returnOrderBook&currencyPair=BTC_{symbol}",
        "trades" => "https://poloniex.com/public?command=returnTradeHistory&currencyPair=BTC_{symbol}"
    );

    public function __construct($coin = false)
    {
        $this->coin = $coin;
    }

    public function overview()
    {
        $formattedData = array();
        $url = $this->urls["overview"];
        $result =  $this->fetchUrl($url, 20);

        foreach($result as $item)
        {
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
        $orders = $this->fetchUrl($this->url("orders"), $this->refreshTime);

        if ($request === 'buy') {
            return $orders->buy;
        } elseif ($request === "sell") {
            return $orders->sell;
        }

        return $orders;

    }

    public function all($time)
    {
        $orders = $this->orders();

        $data = array(
            "time" => time()
        );
        $data["trades"] = $this->fetchNewData($this->url("trades"), $time, $this->refreshTime);
        $data["sells"] = $orders->sell;
        $data["buys"] = $orders->buy;


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
        switch($url)
        {
            case $this->url("overview"):
                return $this->adjustOverview($data);
                break;
            case $this->url("orders"):
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

        foreach ($data as $trade) {
            $newData[] = array(
                "type" => ($trade["type"] == "buy" ? "Buy" : "Sell"),
                "price" => $trade["rate"],
                "total" => $trade["total"],
                "amount" => $trade["amount"],
                "time" => strtotime($trade["date"]),
            );

        }

        return json_encode($newData);
    }

    protected function adjustOrderbook($data)
    {
        $data = json_decode($data, true);

        $newData = array(
            "buy" => array(
                "orders" => $this->buildOrderbook($data["bids"])
            ),
            "sell" => array(
                "orders" => $this->buildOrderbook($data["asks"])
            ),
        );

        //dbg($newData);
        //die();
        return json_encode($newData);
    }

    protected function buildOrderbook($orders)
    {
        foreach($orders as $key => $order)
        {
            $orders[$key] = array(
                "price" => $order[0],
                "count" => $order[1],
                "btc" => $order[0] * $order[1],
            );
        }

        return $orders;
    }

    protected function adjustOverview($data)
    {

        $newData = array();
        $data = json_decode($data, true);

        foreach($data as $key => $price)
        {
            list($base, $symbol) = explode("_",$key);
            if($base !== "BTC") {continue;}
            $newData[] = array(
                "price" => $price,
                "symbol" => $symbol,
                "base" => $base,
                "label" => $base . '/' . $symbol,
                "market" => "poloniex",
            );

        }

        return json_encode($newData);
    }

    private function adjustCoinSymbol($symbol){
        if(strtoupper($symbol) === 'RDD'){
            return "REDD";
        }

        return $symbol;
    }

    private function url($key)
    {
        $url = $this->urls[$key];

        if (is_string($this->coin)) {
            $id = $this->coin;
        } else {
            $id = $this->coin["symbol"];
        }

        $id = $this->adjustCoinSymbol($id);

        return str_replace('{symbol}', $id, $url);
    }
}