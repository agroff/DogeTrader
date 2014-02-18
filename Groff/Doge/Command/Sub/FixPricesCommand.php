<?php namespace Groff\Doge\Command\Sub;

use Groff\Command\Command;
use Groff\Command\Option;
use \ORM;

/**
 * Project: helix
 * Author:  andy
 * Created: 12/18/13 10:56 PM
 */
class FixPricesCommand extends Command
{

    protected $description = "Finds all prices with a value of zero and updates them to be an average of the surrounding values.";

    protected function addOptions()
    {
        $this->addOption(new Option("q", FALSE, "Silence output.", "quiet"));
        //$this->addOption(new Option("n", "User", "The name of the user to say hello to.", "name"));
    }

    /**
     * Contains the main body of the command
     *
     * @return Int Status code - 0 for success
     */
    function main()
    {

        $records = ORM::for_table('price')->where_lt("value", "2")->find_many();

        foreach ($records as $record)
        {
            $this->fixRecord($record);
        }


        return 0;
    }

    private function fixRecord($record)
    {
        $before = $this->getBefore($record);
        $after = $this->getAfter($record);

        $average = round((intval($before->value) + intval($after->value) ) / 2);

        $this->output("Before Value: " . $before->value);
        $this->output("Before Id: " . $before->id);
        $this->output("After Value: " . $after->value);
        $this->output("After Id: " . $after->id);
        $this->output("------------------");
        $this->output("Averaged: " . $average);
        $this->output();

        $record->value = $average;

        $record->save();

    }

    private function getBefore($result)
    {
        return ORM::for_table('price')
            ->where_gt("value", "0")
            ->where_lt("id", $result->id)
            ->where("source", $result->source)
            ->limit(1)
            ->order_by_desc("id")
            ->find_one();
    }

    private function getAfter($result)
    {
        return ORM::for_table('price')
            ->where_gt("value", "0")
            ->where_gt("id", $result->id)
            ->where("source", $result->source)
            ->limit(1)
            ->order_by_asc("id")
            ->find_one();
    }

    private function output($str = "")
    {
        $quiet= $this->option("quiet");
        if(!$quiet)
        {
            echo $str . "\n";
        }
    }
}