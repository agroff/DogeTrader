<?php

define("DOC_ROOT", $_SERVER["DOCUMENT_ROOT"]);
define("ABOVE_ROOT", preg_replace("/[-_a-zA-Z]+\\/?$/", "", DOC_ROOT));

require_once(DOC_ROOT . "/vendor/autoload.php");

use \Groff\Doge\Setting;

$settingsFile = ABOVE_ROOT . "/update/settings.php";

//echo DOC_ROOT . "\n";
//echo ABOVE_ROOT . "\n";

if(file_exists($settingsFile)){
    require_once($settingsFile);
}

if(!defined("CURRENT_COIN")) {
    define("CURRENT_COIN", 'doge');
}


// php returns the error code two when trying to call a method without enough arguments.
// We'll define that here to help avoid magic numbers in the code.
define("DOGE_ERROR_ARGUMENTS", 2);

//We can use these constants to switch between development and production settings.
define("ENVIRONMENT", "development");
//define("ENVIRONMENT", "production");

include(DOC_ROOT . "/helpers/global.php");

function exception_handler($exception)
{
    ob_end_clean();
    //logError($exception);
    showError($exception);
    exit();
}

function error_handler($errno, $errstr, $errfile, $errline)
{
    throw new Exception($errstr, $errno);
}

set_error_handler("error_handler", E_ALL);
set_exception_handler('exception_handler');



$host = Setting::get("database.host");
$name = Setting::coin("database");

$username = Setting::get("database.username");
if(defined("MYSQL_USERNAME")){
    $username = MYSQL_USERNAME;
}

ORM::configure('mysql:host=' . $host . ';dbname=' . $name);
ORM::configure('username', $username);
ORM::configure('password', MYSQL_PASSWORD);

