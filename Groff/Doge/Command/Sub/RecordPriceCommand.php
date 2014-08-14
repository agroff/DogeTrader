<?php namespace Groff\Doge\Command\Sub;

use Groff\Doge\ApiFactory;
use Groff\Command\Command;
use Groff\Command\Option;
use Groff\Doge\Provide\CryptsyMarket;
use Groff\Doge\Setting;
use \ORM;

/**
 * Project: helix
 * Author:  andy
 * Created: 12/18/13 10:56 PM
 */
class RecordPriceCommand extends Command
{

    protected $description = "Inserts price data from the configured data sources.";

    /**
     * Contains the main body of the command
     *
     * @return Int Status code - 0 for success
     */
    function main()
    {
        $force = $this->option("force");
        $mins = intval(date("i"));

        $settingTime = Setting::get("graph.update_minutes");
        if($mins % $settingTime !== 0 && $force != true){
            $this->output("Not Time for update...");
            $this->output("Modulus not zero: " . $mins % $settingTime);
            return;
        }

        $coin = Setting::coin();


        /** @var CryptsyMarket $api */
        $cryptsy = ApiFactory::market("cryptsy", $coin);
        $rates   = ApiFactory::rates("coinbase");

        $satoshi  = $cryptsy->price();

        $btcToUsd = $rates->btcToUsd();

        $usd = ($satoshi * $btcToUsd) / 100000000;
        $usd = intval($usd  * 100000);
        $time = sqlDate("now");

        $price = ORM::for_table('price')->create();

        $price->type = "satoshi";
        $price->source = "cryptsy";
        $price->value = $satoshi;
        $price->time = $time;

        $price->save();

        $price = ORM::for_table('price')->create();

        $price->type = "usd";
        $price->source = "coinbase";
        $price->value = $usd;
        $price->time = $time;

        $price->save();

        $realUsd = "";
        if(Setting::coin("graph.hasVos"))
        {
            $vos = ApiFactory::market("vos", $coin);
            $realUsd  = $vos->price();

            $price = ORM::for_table('price')->create();

            $price->type = "usd";
            $price->source = "vos";
            $price->value = $realUsd;
            $price->time = $time;

            $price->save();
        }

        $this->output("Satoshi: " . $satoshi);
        $this->output("BTC: " . $btcToUsd );
        $this->output("USD: " . $usd);
        $this->output("VOS: " . $realUsd);
        $this->output("Prices Saved.");

        return 0;
    }

    protected function printUsage($scriptName)
    {
        echo "Usage: $scriptName install  \n";
    }

    private function output($str)
    {
        $verbose = $this->option("verbose");
        if($verbose)
        {
            echo $str . "\n";
        }
    }

    protected function addOptions()
    {
        $this->addOption(new Option("v", FALSE, "Write output.", "verbose"));
        $this->addOption(new Option("f", FALSE, "Force an update regardless of the time.", "force"));
    }
}