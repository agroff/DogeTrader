<?php namespace Groff\Doge;

use Groff\Doge\Market\Arbitrage;

class Controller
{

    function index()
    {
        $headerData = $this->getHeaderData();

        $headerData["alarms"] = Setting::get("alarms");

        view("inc/header", $headerData);
        view("index", $headerData);
        view("inc/footer");
    }


    function arbitrage()
    {
        $headerData = $this->getHeaderData();
        $headerData["fullHeader"] = false;
        $arb = new Arbitrage();
        $marketList = array(
            "cryptsy",
            "mintpal",
            "poloniex",
        );

        $markets = array();

        foreach($marketList as $item)
        {
            $market = ApiFactory::market($item, false);
            $markets[] = $market->overview();
        }

        $result = $arb->getComparisons($markets);


        $data = array(
            "arbitrage" => $result,
            "marketList" => $marketList,
        );


        view("inc/header", $headerData);

        view("arbitrage", $data);


        view("inc/footer");
    }


    function getHeaderData()
    {
        /** @var \Groff\Doge\Provide\BlockChainInterface $blockChain */
        $blockChain = ApiFactory::blockChain(CURRENT_COIN);

        $donationAddress = Setting::coin("donation_address");
        $donations = $blockChain->receivedAt($donationAddress);


        $coinName = Setting::coin("name");
        $symbol = Setting::coin("symbol");
        $ucCoinName = ucfirst($coinName);


        $data = array(
            "donations" => $donations,
            "donationAddress" => $donationAddress,
            "symbol" => $symbol,
            "ucCoinName" => $ucCoinName,
            "coinName" => $coinName,
        );

        return $data;
    }

} 