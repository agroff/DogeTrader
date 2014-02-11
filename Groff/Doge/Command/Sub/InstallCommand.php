<?php namespace Groff\Doge\Command\Sub;


use Groff\Command\Command;
use \ORM;

/**
 * Project: helix
 * Author:  andy
 * Created: 12/18/13 10:56 PM
 */
class InstallCommand extends Command
{

    protected $description = "Creates the table(s) for Doge Trader";

    /**
     * Contains the main body of the command
     *
     * @return Int Status code - 0 for success
     */
    function main()
    {
        $db = ORM::get_db();
        $db->exec("CREATE TABLE IF NOT EXISTS `price` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type` enum('satoshi','usd') NOT NULL,
  `source` enum('cryptsy','vos','coinbase') NOT NULL,
  `value` int(11) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;"
        );

        //$res = $this->db()->table('user')->where('id', 6)->get();
        //print_r($res);
        echo "Tables Created \n";
        return 0;
    }

    protected function printUsage($scriptName)
    {
        echo "Usage: $scriptName install  \n";
    }

    protected function addOptions()
    {
    }
}