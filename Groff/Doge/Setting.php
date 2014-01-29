<?php namespace Groff\Doge;

/**
 * Project: dogewatch
 * Author:  andy
 * Created: 1/24/14 11:03 PM
 */
class Setting
{
    private static function getSettings()
    {
        static $settings = null;
        if($settings === null)
        {
            $file     = $_SERVER["DOCUMENT_ROOT"] . '/settings.json';
            $settings = json_decode(file_get_contents($file), TRUE);
        }
        return $settings;
    }

    public static function get($setting)
    {
        $settings = self::getSettings();
        $path = explode(".", $setting);

        while(count($path) > 0)
        {
            $index = array_shift($path);
            $settings = $settings[$index];
        }

        return $settings;
    }

} 