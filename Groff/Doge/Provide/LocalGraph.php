<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2/27/14
 * Time: 6:38 PM
 */

namespace Groff\Doge\Provide;

use Groff\Doge\Setting;
use \ORM;

class LocalGraph extends Cacheable{


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

} 