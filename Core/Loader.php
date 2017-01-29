<?php

namespace Core;

class Loader
{
    public static function registerAutoLoad()
    {
        spl_autoload_register(['\Core\Loader', 'autoload']);
    }

    public static function autoload($class) {
        self::loadClass($class);
    }

    public static function loadClass($class){
        $filePath = '../' . str_replace('\\', '/', $class) . '.php';
        if(!file_exists($filePath)){
            throw new \Exception('File connot be loaded: ' . $filePath);
        }

        include $filePath;
    }
}