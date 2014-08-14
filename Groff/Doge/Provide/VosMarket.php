<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2/27/14
 * Time: 7:08 PM
 */

namespace Groff\Doge\Provide;

use Groff\Doge\Setting;

class VosMarket extends Cacheable implements MarketInterface{

    public function __construct($coin)
    {

    }

    public function last()
    {
        // TODO: Implement last() method.
    }

    public function orders()
    {
        // TODO: Implement orders() method.
    }

    public function all($time)
    {
        // TODO: Implement all() method.
    }


    public function price()
    {
        $url = Setting::get("api.vos_trades_url");
        $orders = $this->fetchUrl($url, 60);
        $buy = $orders->data->bids[0];
        return $buy->price->value_int / 1000;
    }
}