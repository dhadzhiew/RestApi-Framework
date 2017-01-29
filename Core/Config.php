<?php

namespace Core;


class Config
{
    /**
     * @var Config
     */
    private static $instance;
    /**
     * @var string
     */
    private $configFolder;
    /**
     * @var array
     */
    private $configArray;

    private function __construct()
    {
    }

    public function __get($name)
    {
        if(!isset($this->configArray[$name])) {
            $path = $this->configFolder . $name . '.php';
            $file = realpath($path);
            if($file != null && is_file($file) && is_readable($file)) {
                $this->configArray[$name] = include $file;
            } else {
                throw new \Exception('Config file cannot be loaded: ' . $file);
            }
        }

        return $this->configArray[$name];
    }

    public static function getInstance(){
        if(!self::$instance) {
            self::$instance = new Config;
        }

        return self::$instance;
    }

    public function setConfigFolder($folder){
        $configPath = realpath($folder);
        if($configPath != false && is_dir($configPath) && is_readable($configPath)) {
            $this->configFolder = $folder . DIRECTORY_SEPARATOR;
            $this->configArray = [];
        } else {
            throw new \Exception('Invalid folder path.');
        }
    }

    public function getConfigFolder(){
        return $this->configFolder;
    }
}