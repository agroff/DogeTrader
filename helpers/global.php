<?php


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

function showError($error = FALSE)
{
    if(ob_get_level() > 0)
    {
        ob_end_clean();
    }
    view("errors/default", array("error" => $error));
}

function show404()
{
    view("errors/404");
}


function get($key)
{
    if (!isset($_GET[$key])) {
        return false;
    }

    return $_GET[$key];
}

function post($key)
{
    if (!isset($_POST[$key])) {
        return false;
    }

    return $_GET[$key];
}