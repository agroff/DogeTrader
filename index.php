<?php
require_once("init.php");

use Groff\Doge\Controller;

ob_start();

//http rewrite passes the whole string as the GET variable "args"
$arguments = get("args");

//if there are none, then we're requesting the index page.
if($arguments === FALSE)
{
    $arguments = "index";
}

//split the arguments on forward slash
$arguments = explode("/", $arguments);

//the first argument is the controller method
$method = array_shift($arguments);

//instantiate the controller
$controller = new Controller();

//create a callable array with the controller and the string containing the method which was requested.
$callable = array($controller, $method);


if(is_callable($callable))
{
    try
    {
        //try calling the method, passing any extra parameters as variables to the method.
        call_user_func_array($callable, $arguments);
    }
    catch(Exception $e){
        //We'll show a 404 because the URL didn't have enough arguments.
        if($e->getCode() === DOGE_ERROR_ARGUMENTS)
        {
            //show404();
            throw $e;
        }
        //otherwise, a regular uncaught error occured. Re-throw it to be handled by the general error handler.
        else
        {
            throw $e;
        }
    }

}
else
{
    show404();
}


ob_end_flush();

