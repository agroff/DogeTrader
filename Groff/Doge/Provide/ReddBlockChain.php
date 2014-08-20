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
        "receivedAt" => "http://cryptexplorer.com/chain/ReddCoin/q/getreceivedbyaddress/",
        "receivedAtHtml" => "http://bitinfocharts.com/reddcoin/address/"
    );

    protected $cacheMinutes = 45;

    public function receivedAt($address)
    {
        $this->lastAddress = $address;
        $url = $this->url("receivedAtHtml");

        $result = $this->fetchUrl($url, $this->cacheMinutes * 60);

        $pattern = '/<table class="table table-striped table-condensed[^>]+>(.+?)<\/table>/';
        preg_match($pattern, $result->value, $matches);

        $pattern = '/class="text-success[^>]+>(.+?)<\//';
        preg_match_all($pattern, $matches[1], $matches);

        $result = $matches[1][1];
        $result = str_replace("," ,"", $result);

        $pattern = '/(\d+(?:\.\d+)?)/';
        preg_match($pattern, $result, $matches);

        $result = $matches[1];

        return number_format($result);
    }

}