<?php

define("DOC_ROOT", $_SERVER["DOCUMENT_ROOT"]);

require_once(DOC_ROOT . "/vendor/autoload.php");

$settingsFile = DOC_ROOT . "/../update/settings.php";

if(file_exists($settingsFile)){
    require_once($settingsFile);
}

function o($output)
{
    echo htmlentities($output);
}

function cdbg($item){

    if(is_array($item) || is_object($item))
    {
        return print_r($item);
    }

    echo $item . "\n";
}
function dbg($item)
{
    echo "<pre>";
    var_dump($item);
    echo "</pre>";
}

function require_js($file)
{
    require(DOC_ROOT . '/js/' . $file);
}

function view($file, $data = array())
{
    extract($data);
    require(DOC_ROOT . '/view/' . $file . '.php');
}

function sqlDate($date = 'now')
{
    $time = strtotime($date);
    return date('Y-m-d H:i:s', $time);
}

function template($id)
{

    echo '<script type="text/html" id="'.$id.'">';
    view("template/" . $id);
    echo '</script>';

}