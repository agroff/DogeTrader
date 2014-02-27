<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2/27/14
 * Time: 7:06 PM
 */

namespace Groff\Doge\Provide;


class MintBlockChain  extends BlockChain{

    protected $urls = array(
        "receivedAt" => "http://mintcoin-explorer.info/chain/mintcoin/q/getreceivedbyaddress/"
    );

    protected $cacheMinutes = 145;
}