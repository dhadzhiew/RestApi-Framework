<?php

namespace Core;


class Common
{
    /**
     * @param $class
     * @return array
     */
    public static function getAnnotations($class)
    {
        $reflection = new \ReflectionClass($class);
        $methods = $reflection->getMethods();
        $methodsAnnotations = [];
        foreach ($methods as $method) {
            $methodDoc = $method->getDocComment();
            preg_match_all('/@(\w+)\((.+?)\)/', $methodDoc, $matches);
            $methodsAnnotations[$method->name] = [];
            foreach ($matches[1] as $key => $annotation) {
                preg_match_all('/"(.+?)"/', $matches[2][$key], $params);
                $methodsAnnotations[$method->name][$annotation] = $params[1];
            }
        }

        return $methodsAnnotations;
    }

    /**
     * @param $value string
     * @param $min int
     * @param $max int
     * @return bool
     */
    public static function validateStringLength($value, $min = NULL, $max = NULL)
    {
        if (($min == NULL || mb_strlen($value) >= $min) && ($max == NULL || mb_strlen($value) <= $max)) {
            return true;
        }

        return false;
    }

    public static function getUri()
    {
        $scriptNameParts = explode('/', $_SERVER['SCRIPT_NAME']);
        array_pop($scriptNameParts);
        $baseUrl = implode('/', $scriptNameParts);
        $baseUrlLength = strlen($baseUrl);

        $uri = substr($_SERVER['REQUEST_URI'], $baseUrlLength + 1);

        return $uri;
    }
}