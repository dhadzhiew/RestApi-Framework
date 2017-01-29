<?php

namespace Core;


use Core\Interfaces\TranslationInterface;

class DefaultTranslation implements TranslationInterface
{

    /**
     * @param $string
     * @param array $params
     * @return string
     */
    public function __($string, $params = array())
    {
        return $string;
    }
}