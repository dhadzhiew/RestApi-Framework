<?php


namespace Routers;

use Core\Common;
use Core\Exceptions\HttpException;
use Core\Interfaces\ResponseInterface;
use Core\Interfaces\RouterInterface;

class RESTRouter implements RouterInterface
{
    /**
     * @var array
     */
    private $routes;
    /**
     * @var array
     */
    private $params;
    /**
     * @var string
     */
    private $controllerName;
    /**
     * @var string
     */
    private $methodName;

    /**
     * @param $method
     * @param $pattern
     * @param $target
     */
    public function add($method, $pattern, $target)
    {
        $method = strtolower($method);
        $this->routes[$method][$pattern] = $target;
    }

    /**
     * @throws HttpException
     */
    public function dispatch()
    {
        $uri = Common::getUri();
        $httpMethod = strtolower($_SERVER['REQUEST_METHOD']);
        $routes = $this->getRoutesForMethod($httpMethod);
        foreach ($routes as $pattern => $target) {
            $pattern = str_replace('/', '\/', $pattern);
            $regexPattern = preg_replace('/\{(.+?)\}/', '([^\/]+)', $pattern);
            if (preg_match('/^' . $regexPattern . '$/', $uri, $matches)) {
                $target = explode('::', $target);
                $this->setControllerName($target[0]);
                $this->setMethodName($target[1]);
                $params = array_slice($matches, 1);
                $this->setParams($params);
                $this->loadAction();
                return;
            }
        }
        throw new HttpException('Not found', 404);
    }

    /**
     * @throws HttpException
     */
    private function loadAction()
    {
        $controllerName = $this->controllerName;
        $methodName = $this->methodName;
        if (is_callable([$controllerName, $methodName])) {
            $controller = new $controllerName;
            $response = call_user_func_array([$controller, $methodName], $this->getParams());
            if ($response instanceof ResponseInterface) {
                echo $response->render();
            }
            return;
        }

        throw new HttpException('Not found', 404);
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param $params
     */
    private function setParams($params){
        $this->params = $params;
    }

    private function getRoutesForMethod($method)
    {
        if (isset($this->routes[$method])) {
            return $this->routes[$method];
        }

        return [];
    }

    /**
     * @param $methodName
     */
    private function setMethodName($methodName)
    {
        $this->methodName = $methodName;
    }

    /**
     * @param $controllerName
     */
    private function setControllerName($controllerName)
    {
        $this->controllerName = $controllerName;
    }
}
