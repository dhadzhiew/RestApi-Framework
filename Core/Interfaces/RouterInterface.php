<?php

namespace Core\Interfaces;

interface RouterInterface {
    /**
     * @return null
     */
    public function dispatch();

    /**
     * @return array
     */
    public function getParams();
}