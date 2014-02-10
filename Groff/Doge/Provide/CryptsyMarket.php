<?php namespace Groff\Doge\Provide;


use Groff\Doge\Setting;

class CryptsyMarket implements ProviderInterface
{

    public function last()
    {
        $url = Setting::get("api.trades_url");
        return $this->fetchUrl($url);
    }

    public function rates()
    {
        $url = Setting::get("api.rates_url");
        $mins = Setting::get("api.rates_cache_minutes");
        return json_encode($this->fetchUrl($url, 60 * $mins));
    }

    public function orders()
    {
        // TODO: Implement orders() method.
    }

    public function all($time)
    {
        $cacheTime = Setting::get("cache.keep_time");

        $data = array(
            "time" => time(),
            "trades" => $this->fetchNewData("api.trades_url", $time),
            "sells" => $this->fetchNewData("api.sell_orders_url", $time),
            "buys" => $this->fetchNewData("api.buy_orders_url", $time),
        );

//        if(!isset($trades->cached)){
//            $data["trades"] = $trades->aaData;
//        }
//
//        if(!isset($sells->cached)){
//            $data["sells"] = array_slice($sells->aaData, 0, 50);
//        }
//
//        if(!isset($buys->cached)){
//            $data["buys"] = array_slice($buys->aaData, 0, 50);
//        }

        if(!$data["trades"]){
            $data["trades"] = false;
        }
        if(!$data["sells"]){
            $data["sells"] = false;
        }
        if(!$data["buys"]){
            $data["buys"] = false;
        }

        return json_encode($data);
    }

    private function fetchNewData($urlName, $time){
        $url = Setting::get($urlName);
        $file = $this->getFileFromUrl($url);

        $this->updateCache($url);

        //return data if:
        //it has been updated since $time
        if(filemtime($file) > $time || $time === FALSE){
            return json_decode(file_get_contents($file));
        }

        return false;
    }

    private function updateCache($url)
    {
        $cacheTime = Setting::get("cache.keep_time");
        $file = $this->getFileFromUrl($url);

        //update cache if:
        //current cache is old
        //and fetched data is new

        if(!$this->fileOlderThan($file, $cacheTime) && file_exists($file))
        {
            return;
        }

        $data = $this->fetchRawUrl($url);
        $data = $this->adjustData($data, $url);

        if($this->dataIsNew($data, $file)){
            file_put_contents($file, $data);
        }

    }

    private function dataIsNew($data, $file){
        if(!file_exists($file)){
            return true;
        }

        $oldData = file_get_contents($file);

        if(md5($oldData) === md5($data)){
            return false;
        }

        return true;
    }

    private function adjustData($data, $url){
        $isBuy  = $url === Setting::get("api.buy_orders_url");
        $isSell = $url === Setting::get("api.sell_orders_url");
        $isRates = $url === Setting::get("api.rates_url");


        if($isBuy || $isSell){
            $orderbookCount = Setting::get("api.orderbook_count");
            $data = json_decode($data);

            //something went wrong?
            if(empty($data->aaData)){
                return $data;
            }

            $data->aaData = array_slice($data->aaData, 0, $orderbookCount);
            $data = json_encode($data);
        }

        if($isRates){
            $data = $this->adjustCoinbaseRates($data);
        }

        return $data;
    }

    private function adjustCoinbaseRates($data)
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

    private function getFileFromUrl($url)
    {
        return DOC_ROOT . Setting::get("cache.path") . "/" . md5($url);
    }

    private function fetchRawUrl($url)
    {
        $curlSession = $this->curlHandle();
        curl_setopt($curlSession, CURLOPT_URL, $url);
        curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($curlSession);
        curl_close($curlSession);

        return $data;
    }

    private function fetchUrl($url, $cacheTime = FALSE)
    {
        $file = "";

        $cache = FALSE;
        if($cacheTime > 0){
            $cache = TRUE;
        }

        if($cache)
        {
            $file = $this->getFileFromUrl($url);

            $data = $this->getCached($file, $cacheTime);

            if($data !== FALSE)
            {
                $data = json_decode($data);
                $data->cached = true;
                return $data;
            }
        }

        $data = $this->fetchRawUrl($url);
        $data = $this->adjustData($data, $url);

        if($cache)
        {
            file_put_contents($file, $data);
        }
        return json_decode($data);
    }

    /**
     * Returns file contents if not expired
     * @param      $file
     * @param bool $cacheTime
     *
     * @return bool|string
     */
    private function getCached($file, $cacheTime = FALSE)
    {
        if($cacheTime === FALSE) {
            $cacheTime = Setting::get("cache.keep_time");
        }

        if (file_exists($file)) {


            if(!$this->fileOlderThan($file, $cacheTime))
            {
                return file_get_contents($file);
            }
        }

        return FALSE;
    }

    private function fileOlderThan($file, $seconds)
    {
        if (! file_exists($file)) {
            return FALSE;
        }

        $difference = time() - filemtime($file);

        if($difference >= $seconds){
            return TRUE;
        }

        return FALSE;
    }

    private function curlHandle()
    {
//        static $curlHandle = null;
//        if($curlHandle === null)
//        {
//            $curlHandle = curl_init();
//        }
        return curl_init();
    }
}