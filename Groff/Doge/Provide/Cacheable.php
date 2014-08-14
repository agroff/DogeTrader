<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2/27/14
 * Time: 6:28 PM
 */

namespace Groff\Doge\Provide;

use Groff\Doge\Setting;

class Cacheable {



    protected function getCacheIfNewEnough($file, $seconds)
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


    protected function fetchNewData($urlName, $time, $cacheSeconds = FALSE){
//        $url = Setting::get($urlName);
        $url = $urlName;
        $file = $this->getFileFromUrl($url);

        if($cacheSeconds === FALSE) {
            $cacheSeconds = Setting::get("cache.keep_time");
        }

        $this->updateCache($url, $cacheSeconds);

        //return data if:
        //it has been updated since $time
        if(filemtime($file) > $time || $time === FALSE){
            return json_decode(file_get_contents($file));
        }

        return false;
    }

    protected function updateCache($url, $cacheTime)
    {
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
            @chmod($file, 0777);
        }

    }

    protected function dataIsNew($data, $file){
        if(!file_exists($file)){
            return true;
        }

        $oldData = file_get_contents($file);

        if(md5($oldData) === md5($data)){
            return false;
        }

        return true;
    }


    protected function getFileFromUrl($url)
    {
        return DOC_ROOT . Setting::get("cache.path") . "/" . md5($url);
    }

    protected function fetchRawUrl($url)
    {
        $curlSession = $this->curlHandle();
        curl_setopt($curlSession, CURLOPT_URL, $url);
        curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($curlSession);
        curl_close($curlSession);

        return $data;
    }

    protected function fetchUrl($url, $cacheTime = FALSE)
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
                if(is_object($data))
                {
                    $data->cached = true;
                }
                return $data;
            }
        }

        $data = $this->fetchRawUrl($url);
        $data = $this->adjustData($data, $url);

        if($cache)
        {
            file_put_contents($file, $data);
            @chmod($file, 0777);
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
    protected function getCached($file, $cacheTime = FALSE)
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

    protected function fileOlderThan($file, $seconds)
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

    protected function curlHandle()
    {
        return curl_init();
    }

    //overwrite if necessary
    protected function adjustData($data, $url){
        return $data;
    }

} 