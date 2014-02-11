<?php namespace Groff\Doge\Command;


/**
 * Project: helix
 * Author:  andy
 * Created: 12/18/13 10:54 PM
 */
class SubCommandFactory
{

    public function one($name)
    {
        $className = ucwords($name);
        $className = str_replace(" ", "", $className);
        $className = 'Groff\\Doge\\Command\\Sub\\' . $className . 'Command';

        if(!class_exists($className, TRUE))
        {
            throw new \Exception("Could not load class `$className`. ");
        }

        return new $className();
    }

    public function all()
    {
        $all = array();
        $names = $this->getAllCommandNames();
        foreach($names as $name)
        {
            $all[$name] = $this->one($name);
        }

        return $all;
    }

    private function getAllCommandNames()
    {
        $files = scandir(__DIR__ . '/Sub');
        $commandNames = array();
        foreach($files as $file)
        {
            if(strpos($file, "Command.php"))
            {
                $name = str_replace("Command.php", '', $file);
                //$name = strtolower($name);
                $commandNames[] = $name;
            }
        }

        return $commandNames;
    }

} 