<?php

define("DOC_ROOT", $_SERVER["DOCUMENT_ROOT"]);

require_once(DOC_ROOT . "/vendor/autoload.php");

function o($output)
{
    echo htmlentities($output);
}

function dbg($item)
{
    echo "<pre>";
    var_dump($item);
    echo "</pre>";
}