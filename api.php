<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/init.php");

use Groff\Utils\Input;
use Groff\Doge\Setting;
use Groff\Doge\ApiFactory;

$coin = Setting::coin();

$requestedMarket = strtolower(Input::get("market"));

/** @var \Groff\Doge\Provide\MarketInterface $market */
$market = ApiFactory::market($requestedMarket, $coin);
$rates = ApiFactory::rates("coinbase");
$graph = ApiFactory::graph($coin);

$method = Input::get("method");
$time = Input::get("time");
$days = Input::get("days");

switch($method)
{
    case "last":
        echo $market->last();
        break;

    case "rates":
        echo $rates->rates();
        break;

    case "all":
        echo $market->all($time);
        break;

    case "graph":
        echo $graph->graph($days);
        break;

    case "orders":
        $market->orders();
        break;

    case "checkTrade":
        $arb = new \Groff\Doge\Market\Arbitrage();
        echo json_encode( $arb->checkTrade(Input::get("currency"), Input::get("buy"), Input::get("sell")) );
        break;
}

//dbg(Setting::get("api.trades_url"));
