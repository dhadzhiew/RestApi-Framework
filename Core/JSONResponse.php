<?php


namespace Core;


class JSONResponse implements  Interfaces\ResponseInterface
{
    /**
     * @var int
     */
    private $statusCode = 200;
    /**
     * @var array
     */
    private $data = [];

    /**
     * @param int $statusCode
     * @return null
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $data
     * @return null
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $options
     * @return null
     */
    public function render($options = array())
    {
        header('Content-type: application/json');
        http_response_code($this->getStatusCode());
        echo json_encode($this->getData());
    }
}