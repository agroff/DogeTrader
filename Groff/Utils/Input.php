<?php namespace Groff\Utils;

/**
 * Project: dogewatch
 * Author:  andy
 * Created: 1/24/14 10:51 AM
 */
class Input
{

    public static function get($item)
    {
        if (isset($_GET[$item])) {
            return $_GET[$item];
        }

        return false;
    }

} 