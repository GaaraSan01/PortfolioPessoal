<?php


class Autoloader
{
  
    public static function register()
    {
        spl_autoload_register([__CLASS__, 'load']);
    }

    private static function load($className)
    {

        $paths = [
            ROOT . '/core/' . $className . '.php',
            ROOT . '/app/controllers/' . $className . '.php',
            ROOT . '/app/models/' . $className . '.php',
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                require_once $path;
                return true;
            }
        }

        return false;
    }
}
