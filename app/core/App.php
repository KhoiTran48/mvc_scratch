<?php

use app\core\Router;

class App
{
    private $router;
    private static $config;

    public function __construct()
    {
        new Autoload(self::$config["rootPath"]);
        $this->router = new Router(self::$config["basePath"]);
    }

    public static function setConfig($config)
    {
        self::$config = $config;
    }

    public static function getConfig()
    {
        return self::$config;
    }

    public function run()
    {
        $this->router->run();
    }
}
