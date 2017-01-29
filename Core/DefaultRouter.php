<?php

namespace Core;

use Core\Exceptions\HttpException;
use Core\Interfaces\ResponseInterface;
use Core\Interfaces\RouterInterface;

class DefaultRouter implements RouterInterface
{
    private $params;
    private $controller;
    private $actionName;
    private $httpMethod;
    private $config;

    public function __construct()
    {
        $app = App::getInstance();
        $this->config = $app->getConfig();
    }

    public function dispatch()
    {
        $this->parse();
        $controllerName = 'Controllers\\' . ucfirst($this->controller) . 'Controller';
        try {
            $controller = new $controllerName();
            $methods = Common::getAnnotations($controller);
        } catch (\Exception $e) {
            throw new HttpException('Not found', 404);
        }

        foreach ($methods as $method => $annotations) {
            if (isset($annotations['Route'])) {
                $httpMethod = strtolower($annotations['Route'][0]);
                $name = $annotations['Route'][1];
                if ($httpMethod == strtolower($this->httpMethod) && $name == $this->actionName) {
                    if (!is_callable([$controller, $method])) {
                        throw new \HttpException('Not found', 404);
                    }
                    $response = call_user_func_array([$controller, $method], $this->params);
                    if ($response instanceof ResponseInterface) {
                        echo $response->render();
                    }
                    return;
                }
            }
        }
        throw new HttpException('Not found', 404);
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }


    /**
     * @param string $httpMethod
     */
    private function setHttpMethod($httpMethod)
    {
        $this->httpMethod = $httpMethod;
    }

    /**
     * @return string
     */
    private function getDefaultController()
    {
        if (isset($this->config->app['defaultController'])) {
            return $this->config->app['defaultController'];
        }

        return 'Index';
    }

    private function getDefaultActionName()
    {
        if (isset($this->config->app['defaultActionName'])) {
            return $this->config->app['defaultActionName'];
        }

        return 'index';
    }

    /**
     * Parse uri params
     */
    private function parse()
    {
        $uri = Common::getUri();
        $params = explode("/", $uri);

        $this->controller = array_shift($params);
        $this->actionName = array_shift($params);
        $this->params = $params;
        $this->setHttpMethod($_SERVER['REQUEST_METHOD']);
        if (!$this->controller) {
            $this->controller = $this->getDefaultController();
        }
        if (!$this->actionName) {
            $this->actionName = $this->getDefaultActionName();
        }
    }
}