<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2/27/14
 * Time: 6:38 PM
 */

namespace Groff\Doge\Provide;
use Groff\Doge\Setting;


class CoinbaseRates extends Cacheable{


    public function rates()
    {
        $url = Setting::get("api.rates_url");
        $mins = Setting::get("api.rates_cache_minutes");
        return json_encode($this->fetchUrl($url, 60 * $mins));
    }

    public function btcToUsd()
    {
        $url = Setting::get("api.rates_url");
        $data = $this->fetchUrl($url, 60 * 4);

        $btc = $data->USD->now;
        return $btc;
    }

    protected function adjustData($data, $url)
    {
        return $this->adjustRates($data);
    }

    private function adjustRates($data)
    {
        $data = json_decode($data, TRUE);
        $needle = "btc_to_";
        $newData = array();

        foreach($data as $key => $value)
        {
            if(strpos($key, $needle) === 0){
                $newKey = strtoupper(str_replace($needle, "", $key));
                $newData[$newKey] = array("now" => $value);
            }
        }

        return json_encode($newData);
    }
} 