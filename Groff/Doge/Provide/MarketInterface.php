<?php namespace Groff\Doge\Provide;

/**
 * Project: dogewatch
 * Author:  andy
 * Created: 1/24/14 4:42 PM
 */
interface MarketInterface {
    public function last();
    public function orders();
    public function all($time);
    public function price();
}