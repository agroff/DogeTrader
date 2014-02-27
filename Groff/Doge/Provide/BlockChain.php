<?php namespace Groff\Doge\Provide;

/**
 * Project: dogewatch
 * Author:  andy
 * Created: 1/24/14 4:42 PM
 */
class BlockChain extends Cacheable{

    protected $urls = array();

    protected $cacheMinutes = 120;

    protected $lastAddress;

    public function receivedAt($address)
    {
        $this->lastAddress = $address;
        $url = $this->url("receivedAt");

        $result = $this->fetchUrl($url, $this->cacheMinutes * 60);

        return number_format($result->value);
    }

    protected function adjustData($data, $url)
    {
        $data = array(
            "value" => $data
        );
        $data = json_encode($data);

        return $data;
    }

    private function url($key)
    {
        return $this->urls[$key] . $this->lastAddress;
    }

}