<?php

namespace Core\Exceptions;


class ModelException extends \Exception
{
    private $errors;

    public  function __construct($errors, $code = 400)
    {
        parent::__construct('Invalid model data.', $code);
        $this->setErrors($errors);
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param mixed $errors
     */
    private function setErrors($errors)
    {
        $this->errors = $errors;
    }


}