<?php

use app\core\Router;

class App
{
    private $router;
    private static $config;

    public function __construct()
    {
        new Autoload;
        $this->router = new Router;
        $this->router->get("/home/{username}", "HomeController@index");
        $this->router->get("/user/{username}/{password}", function($username, $pass){
            echo "Day la trang user<br>";
            echo "username: " . $username.'<br>';
        });
        $this->router->any("/list", function(){
            echo "Day la trang list";
        });
        $this->router->get("*", function(){
            echo "404 not found";
        });

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
        $this->router ->run();
    }
}
