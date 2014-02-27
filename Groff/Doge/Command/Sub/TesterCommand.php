<?php namespace Groff\Doge\Command\Sub;

use Groff\Command\Command;
use Groff\Command\Option;

/**
 * Project: helix
 * Author:  andy
 * Created: 12/18/13 10:56 PM
 */
class TesterCommand extends Command
{

    protected $description = "Just a test command for a usage example. Says hello to the user.";

    protected function addOptions()
    {
        $this->addOption(new Option("n", "User", "The name of the user to say hello to.", "name"));
    }

    /**
     * Contains the main body of the command
     *
     * @return Int Status code - 0 for success
     */
    function main()
    {
        cdbg("Total Rows:");
        cdbg(\ORM::for_table('price')->count());

        $name = $this->option("name");
        echo "Hello, $name! \n";
        return 0;
    }
}