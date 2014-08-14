<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 3/16/14
 * Time: 2:06 PM
 */

namespace Groff\Doge\Market;


use Groff\Doge\ApiFactory;

class Arbitrage
{

    private $listOfAllCurrencies = array();
    private $arbitrage = array();


    public function getComparisons($markets)
    {
        foreach ($markets as $market) {
            $this->gatherAllCurrencies($market);
        }

        foreach ($this->listOfAllCurrencies as $currency) {
            $this->calculateArbitrage($currency, $markets);
        }

        uasort($this->arbitrage, array($this, "sortArbitrage"));

        return $this->arbitrage;
    }

    public function checkTrade($currency, $buyAt, $sellAt)
    {

        $buy = ApiFactory::market($buyAt, $currency);
        $sell = ApiFactory::market($sellAt, $currency);

        $canBuy = $buy->orders("sell");
        $canSell = $sell->orders("buy");

        $canBuy = $canBuy->orders[0];
        $canSell = $canSell->orders[0];

        if ($canBuy->count > $canSell->count) {
            $canBuy = $this->matchOrders($canBuy, $canSell->count);
        } else {
            $canSell = $this->matchOrders($canSell, $canBuy->count);
        }

        $trade = array(
            "count" => round($canBuy->count, 4),
            "symbol" => $currency,
            "spend" => round($canBuy->btc, 8),
            "receive" => round($canSell->btc, 8),
            "profit" => round($canSell->btc - $canBuy->btc, 8)
        );

        $trade["percent_gain"] = round(($trade["profit"] / $trade["spend"]) * 100, 2);

        return $trade;
    }

    private function matchOrders($order, $count)
    {
        $order->count = $count;
        $order->btc = $order->price * $count;
        return $order;
    }

    private function gatherAllCurrencies($market)
    {
        foreach ($market as $key => $data) {
            $this->listOfAllCurrencies[$key] = $key;
        }
    }

    private function calculateArbitrage($currency, $markets)
    {
        $instances = array();

        $high = false;
        $low = false;
        $buyAt = "";
        $sellAt = "";
        foreach ($markets as $market) {
            if (isset($market[$currency])) {

                /** @var CurrencyOverview $current */
                $current = $market[$currency];
                $instances[$current->market] = $current;

                $current->price = $current->price * 100000000;

                if ($high === false) {
                    $high = $current->price;
                    $sellAt = $current->market;
                }

                if ($current->price > $high) {
                    $high = $current->price;
                    $sellAt = $current->market;
                }

                if ($low === false) {
                    $low = $current->price;
                    $buyAt = $current->market;
                }


                if ($current->price < $low) {
                    $low = $current->price;
                    $buyAt = $current->market;
                }
            }
        }

        $difference = $high - $low;
        if ($low > 0) {
            $percent = ($difference / $low) * 100;
        } else {
            $percent = 0;
        }


        $this->arbitrage[] = array(
            "currency" => $currency,
            "high" => $high,
            "low" => $low,
            "buyAt" => $buyAt,
            "sellAt" => $sellAt,
            "percent" => $percent,
            "list" => $instances,
        );

    }

    private function sortArbitrage($a, $b)
    {
//        dbg($a);
//        dbg($b);
        return $b["percent"] - $a["percent"];
    }

} 