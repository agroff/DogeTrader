<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 3/16/14
 * Time: 1:18 PM
 */

namespace Groff\Doge\Market;


class CurrencyOverview
{
    public $price;
    public $symbol;
    public $base;
    public $label;
    public $market;

    public function __construct($data)
    {
        foreach ($data as $key => $item) {
            if(property_exists($this, $key))
            {
                $this->{$key} = $item;
            }
        }
    }

} 