<?php

namespace Core;


class BaseController extends Base
{
    /**
     * @var InputData
     */
    private $inputData;
    /**
     * @var App
     */

    public function __construct()
    {
        parent::__construct();
        $this->inputData = InputData::getInstance();
    }

    /**
     * @return InputData
     */
    public function getInputData()
    {
        return $this->inputData;
    }
}