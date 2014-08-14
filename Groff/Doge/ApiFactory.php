<?php namespace Groff\Doge;

use Groff\Doge\Provide\BlockChainInterface;
use Groff\Doge\Provide\CoinbaseRates;
use Groff\Doge\Provide\CryptsyMarket;
use Groff\Doge\Provide\MintpalMarket;
use Groff\Doge\Provide\PoloniexMarket;
use Groff\Doge\Provide\VosMarket;
use Groff\Doge\Provide\DogeBlockChain;
use Groff\Doge\Provide\LocalGraph;
use Groff\Doge\Provide\MarketInterface;
use Groff\Doge\Provide\MintBlockChain;
use Groff\Doge\Provide\ReddBlockChain;

/**
 * Project: dogewatch
 * Author:  andy
 * Created: 1/24/14 4:41 PM
*/

class ApiFactory {

    /**
     * @param string $provider
     * @param string $coin
     *
     * @return bool|MarketInterface
     */
    public static function market($provider, $coin)
    {
        switch($provider)
        {
            case "cryptsy":
                return new CryptsyMarket($coin);
            case "mintpal":
                return new MintpalMarket($coin);
            case "poloniex":
                return new PoloniexMarket($coin);
            case "vos":
                return new VosMarket($coin);
        }
        return false;
    }
    /**
     * @param string $provider
     *
     * @return bool|CoinbaseRates
     */
    public static function rates($provider)
    {
        switch($provider)
        {
            case "coinbase":
                return new CoinbaseRates();
        }
        return false;
    }

    /**
     * @param string $coin
     *
     * @return bool|LocalGraph
     */
    public static function graph($coin)
    {
        return new LocalGraph($coin);
    }

    /**
     * @param string $coin
     *
     * @return bool|BlockChainInterface
     */
    public static function blockChain($coin)
    {
        switch($coin){
            case 'doge':
                return new DogeBlockChain();
            case 'mint':
                return new MintBlockChain();
            case 'redd':
                return new ReddBlockChain();
        }
        return FALSE;
    }
} 