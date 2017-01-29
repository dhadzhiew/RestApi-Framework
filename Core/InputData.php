<?php

namespace Core;


class InputData
{
    /**
     * @var InputData
     */
    private static $instance;
    private $data = [];

    private function __construct()
    {
        if(isset($_SERVER['CONTENT_LENGTH'])){
            parse_str(file_get_contents('php://input', false, null, -1, $_SERVER['CONTENT_LENGTH']), $this->data);
        }
    }


    public function __get($name)
    {
        if(isset($this->data[$name])) {
            return $this->data[$name];
        }

        return null;
    }

    public static function getInstance(){
        if(!self::$instance) {
            self::$instance = new InputData();
        }

        return self::$instance;
    }
}