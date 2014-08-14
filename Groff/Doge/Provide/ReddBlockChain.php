<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2/27/14
 * Time: 7:06 PM
 */

namespace Groff\Doge\Provide;


class ReddBlockChain  extends BlockChain{

    protected $urls = array(
        "receivedAt" => "http://cryptexplorer.com/chain/ReddCoin/q/getreceivedbyaddress/"
    );

    protected $cacheMinutes = 145;
}