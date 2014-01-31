<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/init.php");

use Groff\Utils\Input;
use Groff\Doge\Setting;
use Groff\Doge\ApiFactory;

$api = ApiFactory::get("cryptsy");

$method = Input::get("method");
$time = Input::get("time");

switch($method)
{
    case "last":
        echo $api->last();
        break;

    case "rates":
        echo $api->rates();
        break;

    case "all":
        echo $api->all($time);
        break;

    case "orders":
        $api->orders();
        break;
}

//dbg(Setting::get("api.trades_url"));
