<?php

namespace Core;

require_once 'Loader.php';

use Core\Exceptions\HttpException;
use Core\Interfaces\RouterInterface;
use Core\Interfaces\TranslationInterface;

class App
{
    /**
     * @var App
     */
    private static $instance;

    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var \PDO
     */
    private $db;

    /**
     * @var TranslationInterface
     */
    private $translation;

    private function __construct()
    {
        $this->setErrorHandler($this, 'exceptionHandler');
        \Core\Loader::registerAutoLoad();
        $this->config = Config::getInstance();
    }

    public function run()
    {
        $this->setDefaults();
        $this->router->dispatch();
    }

    /**
     * @return RouterInterface
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param RouterInterface $router
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @return App
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new App();
        }

        return self::$instance;
    }

    /**
     * @param $class
     * @param $method
     */
    public function setErrorHandler($class, $method)
    {
        set_exception_handler([$class, $method]);
    }

    /**
     * @param $folder
     */
    public function setConfigFolder($folder)
    {
        $this->config->setConfigFolder($folder);
    }

    /**
     * @param \Exception $e
     * @throws \Exception
     */
    public function exceptionHandler(\Exception $e)
    {
        $this->log($e->__toString());
        $response = new JSONResponse();
        if ($e instanceof HttpException) {
            $response->setStatusCode($e->getCode());
            $response->setData(['message' => $e->getMessage()]);
        } elseif ($this->config->app['debug']) {
            throw $e;
        } else {
            $response->setStatusCode(500);
            $response->setData(['message' => $this->translation->__($this->config->app['messages']['internal_error'])]);
        }

        echo $response->render();
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param TranslationInterface $translation
     */
    public function setTranslation(TranslationInterface $translation)
    {
        $this->translation = $translation;
    }

    /**
     * @return TranslationInterface
     */
    public function getTranslation()
    {
        return $this->translation;
    }

    /**
     * @return \PDO
     */
    public function getDbConnection()
    {
        if (!$this->db) {
            $dbConfig = $this->config->database;
            $this->db = new \PDO(
                $dbConfig['dsn'],
                $dbConfig['username'],
                $dbConfig['password']);
        }

        return $this->db;
    }

    private function setDefaults()
    {
        if (!$this->config->getConfigFolder()) {
            $this->config->setConfigFolder('../config');
        }
        if (!$this->router) {
            $this->setRouter(new \Core\DefaultRouter());
        }
        if (!$this->translation) {
            $this->setTranslation(new DefaultTranslation());
        }
    }

    /**
     * @param $string
     * @param string $type
     */
    public function log($string, $name = '', $type = 'exception')
    {
        $logFolder = isset($this->getConfig()->app['logFolder']) ? $this->getConfig()->app['logFolder'] : '../log/';
        $msg = '----' . $name . '----' . PHP_EOL;
        $msg .= date('d.m.Y H:i') . PHP_EOL;
        $msg .= Common::getUri() . PHP_EOL;
        $msg .= $_SERVER['REMOTE_ADDR'] . PHP_EOL;
        $msg .= $string . PHP_EOL;
        $msg .= '--------' . PHP_EOL . PHP_EOL;
        file_put_contents($logFolder . $type . '.log', $msg, FILE_APPEND);
    }
}