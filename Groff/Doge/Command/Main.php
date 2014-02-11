<?php namespace Groff\Doge\Command;

//use Groff\Command\Command;
//use Groff\Command\Option;

/**
 * Project: helix
 * Author:  andy
 * Created: 12/17/13 9:33 PM
 */
class Main extends \Groff\Command\Command
{
    protected $description = "A command line interface which facilitates the running of sub-commands.";

    /** @var \Groff\Doge\Command\SubCommandFactory|null  */
    private $subCommandFactory;

    public function __construct($subCommandFactory = null)
    {
        parent::__construct();

        if(is_null($subCommandFactory))
        {
            $subCommandFactory = new SubCommandFactory();
        }

        $this->subCommandFactory = $subCommandFactory;
    }

    protected function addHelpCommand(){
        //help is output when a subcommand isn't specified, rather than with a -help option
    }

    protected function printDescription()
    {
        echo "    " . $this->description . "\n";
        echo "    Available Commands: \n";
        $names = $this->subCommandFactory->all();
        foreach($names as $name => $command)
        {
            echo "    " . str_pad($name, 20, " ", STR_PAD_RIGHT) . $command->getDescription() . "\n";
        }
    }

    protected function printUsage($scriptName)
    {
        echo "Usage: $scriptName subcommand [options] \n";

    }

    /**
     * Contains the main body of the command
     *
     * @return Int Status code - 0 for success
     */
    function main()
    {
        $subCommand = $this->argument(0);

        if(empty($subCommand))
        {
            return $this->printHelp();
        }

        $Command = $this->subCommandFactory->one($subCommand);

        $Command->setDb($this->db());
        $opts = $Command->getRawOptions();

        unset($opts[1]);
        $Command->setRawOptions($opts);

        $Command->run();
    }
}