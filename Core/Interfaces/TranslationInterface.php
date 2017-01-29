<?php

namespace Core\Interfaces;

interface TranslationInterface {
    /**
     * @param $string
     * @param array $params
     * @return string
     */
    public function __($string, $params = array());
}