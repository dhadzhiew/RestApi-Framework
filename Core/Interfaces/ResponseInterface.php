<?php

namespace Core\Interfaces;

interface ResponseInterface {
    /**
     * @param $statusCode int
     * @return mixed
     */
    public function setStatusCode($statusCode);

    /**
     * @return int
     */
    public function getStatusCode();

    /**
     * @param $data
     * @return mixed
     */
    public function setData($data);

    /**
     * @return array
     */
    public function getData();

    /**
     * @param array $options
     * @return string
     */
    public function render($options = array());
}