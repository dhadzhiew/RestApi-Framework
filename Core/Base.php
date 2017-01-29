<?php

namespace Core;


class Base
{
    /**
     * @var App
     */
    private $app;
    public function __construct()
    {
        $this->app = App::getInstance();
    }

    /**
     * @return App
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * @param $string
     * @param array $options
     * @return string
     */
    public function __($string, $options = array())
    {
        return $this->app->getTranslation()->__($string, $options);
    }
}