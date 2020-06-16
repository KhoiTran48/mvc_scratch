<?php

use app\core\Registry;
use app\core\Router;

class App
{
    private $router;
    private static $controller;
    private static $action;

    public function __construct($config)
    {
        new Autoload($config["rootPath"]);
        Registry::getInstance()->config = $config;
        $this->router = new Router($config["basePath"]);
    }

    public static function setController($controller)
    {
        self::$controller = $controller;
    }

    public static function getController()
    {
        return self::$controller;
    }

    public static function setAction($action)
    {
        self::$action = $action;
    }

    public static function getAction()
    {
        return self::$action;
    }

    public function run()
    {
        $this->router->run();
    }
}
