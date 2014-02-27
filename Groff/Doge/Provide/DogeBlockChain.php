<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2/27/14
 * Time: 7:06 PM
 */

namespace Groff\Doge\Provide;


class DogeBlockChain  extends BlockChain {

    protected $urls = array(
        "receivedAt" => "http://dogechain.info/chain/Dogecoin/q/getreceivedbyaddress/"
    );

    protected $cacheMinutes = 45;
}