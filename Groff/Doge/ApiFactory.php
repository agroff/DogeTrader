<?php namespace Groff\Doge;

use Groff\Doge\Provide\CryptsyMarket;
use Groff\Doge\Provide\ProviderInterface;

/**
 * Project: dogewatch
 * Author:  andy
 * Created: 1/24/14 4:41 PM
*/

class ApiFactory {

    /**
     * @param string $provider
     *
     * @return bool|ProviderInterface
     */
    public static function get($provider)
    {
        switch($provider)
        {
            case "cryptsy":
                return new CryptsyMarket();
        }
        return false;
    }
} 