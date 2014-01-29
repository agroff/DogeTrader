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
        return $this->fetchUrl($url, TRUE, 60 * 60);
    }

    public function orders()
    {
        // TODO: Implement orders() method.
    }

    private function fetchUrl($url, $cache = TRUE, $cacheTime = FALSE)
    {

        $file = "";
        if($cache)
        {
            $file = DOC_ROOT . Setting::get("cache.path") . "/" . md5($url);

            $data = $this->getCached($file, $cacheTime);

            if($data !== FALSE)
            {
                $data = json_decode($data);
                //dbg($data);
                $data->cached = true;
                return json_encode($data);
            }
        }

        $curlSession = $this->curlHandle();
        curl_setopt($curlSession, CURLOPT_URL, $url);
        curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($curlSession);
        curl_close($curlSession);

        if($cache)
        {
            file_put_contents($file, $data);
        }
        return $data;
    }

    private function getCached($file, $cacheTime = FALSE)
    {
        if($cacheTime === FALSE) {
            $cacheTime = Setting::get("cache.keep_time");
        }
        if (file_exists($file)) {

            $difference = time() - filemtime($file);

            if($difference <= $cacheTime)
            {
                return file_get_contents($file);
            }
        }

        return FALSE;
    }

    private function curlHandle()
    {
        static $curlHandle = null;
        if($curlHandle === null)
        {
            $curlHandle = curl_init();
        }
        return $curlHandle;
    }
}