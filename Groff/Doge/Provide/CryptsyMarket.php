<?php namespace Groff\Doge\Provide;


use Groff\Doge\Setting;
use \ORM;

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

    public function cryptsyPrice()
    {
        $url = Setting::get("api.trades_url");
        $trades = $this->fetchUrl($url, 60);
        $first = array_shift($trades->aaData);
        $btc = $first[2];
        list($zero, $satoshi) = explode('.', $btc);
        $satoshi = intval($satoshi);
        return $satoshi;
    }

    public function btcToUsd()
    {
        $url = Setting::get("api.rates_url");
        $data = $this->fetchUrl($url, 60 * 4);

        $btc = $data->USD->now;
        return $btc;
    }

    public function vosPrice()
    {
        $url = Setting::get("api.vos_trades_url");
        $orders = $this->fetchUrl($url, 60);
        $buy = $orders->data->bids[0];
        return $buy->price->value_int;
//        $first = array_shift($trades->aaData);
//        $btc = $first[2];
//        list($zero, $satoshi) = explode('.', $btc);
//        $satoshi = intval($satoshi);
//        return $satoshi;
    }

    public function graph($days)
    {
        $url = "api.php?method=graph&days=".$days;

        $file = $this->getFileFromUrl($url);

        $results = $this->getCacheIfNewEnough($file, 4.8 * 60);
//        $results = $this->getCacheIfNewEnough($file, 6);

        if($results !== FALSE)
        {
            //return $results;
        }

        $results = $this->getGraphData($days);

        $results = json_encode($results);

        file_put_contents($file, $results);

        return $results;
    }

    private function getCacheIfNewEnough($file, $seconds)
    {
        if(!file_exists($file))
        {
            return FALSE;
        }

        if($this->fileOlderThan($file, $seconds))
        {
            return FALSE;
        }

        return file_get_contents($file);
    }


    private function getGraphData($days)
    {
        $maxDataPoints = Setting::get("graph.max_data_points");
        $hours = $days * 24;
        $time = sqlDate("-".$hours." hours");
        $results = array(
            "cryptsy" => array(),
            "vos" => array(),
            "coinbase" => array(),
            "since" => $time,
            "counts" => array()
        );

        $price = ORM::for_table('price')->where_gt('time', $time)->find_array();

        foreach($price as $result)
        {
            $src = $result["source"];
            $results[$src][] = $result;

//            if(!isset($results["counts"][$src])){
//                $results["counts"][$src] = 0;
//            }
//            $results["counts"][$src]++;
        }

        $results = $this->trimResultSet($results, "cryptsy", $maxDataPoints);
        $results = $this->trimResultSet($results, "coinbase", $maxDataPoints);
        $results = $this->trimResultSet($results, "vos", $maxDataPoints);

        return $results;
    }

    private function trimResultSet($results, $src, $maxDataPoints)
    {
        $results[$src] = $this->trimGraphData($results[$src], $maxDataPoints);
        $results["counts"][$src] = count($results[$src]);

        return $results;
    }

    private function trimGraphData($originalArray, $desiredCount){

        if(count($originalArray) <= $desiredCount)
        {
            return $originalArray;
        }

        $newArray = array();
        $trimmedLength = count($originalArray) - 2;
        $trimmedDesiredCount  = $desiredCount - 2;

        $newArray[0] = $originalArray[0];

        $i = 0;
        $j = 0;
        while($j < $trimmedLength)
        {
            $diff = ($i + 1) * $trimmedLength - ($j + 1) * $trimmedDesiredCount;
            if($diff < $trimmedLength / 2)
            {
                $i += 1;
                $j += 1;
                $newArray[$i] = $originalArray[$j];
            }
            else{
                $j+= 1;
            }
        }

        $newArray[$trimmedDesiredCount + 1] = $originalArray[$trimmedLength + 1];

        return $newArray;
    }

    public function getReceivedAt($address)
    {
        $url = Setting::get("api.address.received_url").$address;

        $result = $this->fetchUrl($url, 60 * 60);
//        dbg($result);
        return number_format($result->value);
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
        $isReceivedValue  = strpos($url, Setting::get("api.address.received_url")) !== FALSE;


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

        if($isReceivedValue){
            $data = array(
                "value" => $data
            );
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