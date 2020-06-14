<?php

namespace app\core;

class Router
{
    private $routers = array();

    public function __construct()
    {
    }

    public function getRequestURL()
    {
        $uri = $_SERVER["REQUEST_URI"] ?: "/";
        $endPoint = str_replace(\App::getConfig()["basePath"], "", $uri);
        return !empty($endPoint) ? $endPoint : "/";
    }

    private function addRouter($method, $url, $action)
    {
         $this->routers[] = array($method, $url, $action);
    }

    public function get($url, $action)
    {
        $this->addRouter("GET", $url, $action);
    }

    public function post($url, $action)
    {
        $this->addRouter("POST", $url, $action);
    }

    public function any($url, $action)
    {
        $this->addRouter("GET|POST", $url, $action);
    }

    private function getRequestMethod()
    {
        return $_SERVER["REQUEST_METHOD"] ?: "GET";
    }

    private function map()
    {
        $requestURL = $this->getRequestURL();
        $requestMethod = $this->getRequestMethod();
        $params = array();
        foreach ($this->routers as $router) {
            $matchRoute = false;
            list($method, $url, $action) = $router;
            
            if($url == "*"){
                $matchRoute = true;
            }elseif(strpos($method, $requestMethod) !== false){
                if(strpos($url, "{") !== false && strpos($url, "}") !== false){
                    $params = $this->getParamFromURL($url, $requestURL);
                    if(empty($params)){
                        continue;
                    }
                    $matchRoute = true;
                }
                if(strtolower($requestURL) == strtolower($url)){
                    $matchRoute = true;
                }
            }
            if($matchRoute){
                if(is_callable($action)){
                    call_user_func_array($action, $params);
                    return;
                }elseif(is_string($action)){
                    $this->compileRoute($action, $params);
                    return;
                }
            }
        }
        throw new Exception("no route");
    }

    private function compileRoute($action, $params)
    {
        $controlMix = explode("@", $action);
        if(count($controlMix) != 2){
            die("route error");
        }
        list($class, $method) = $controlMix;
        $classNamespace = "app\\controllers\\{$class}";
        if(class_exists($classNamespace)){
            $obj = new $classNamespace;
            if(method_exists($classNamespace, $method)){
                call_user_func_array(array($obj, $method), $params);
            }else{
                die("Method {$method} not found");
            }
        }else{
            die("class {$classNamespace} not found");
        }
    }

    private function getParamFromURL($url, $requestURL)
    {
        $params = array();
        $routeParams = explode("/", $url);
        $requestParams = explode("/", $requestURL);
        if(count($routeParams) != count($requestParams)){
            return $params;
        }
        $routeParams = explode("/", $url);
        $requestParams = explode("/", $requestURL);
        
        foreach ($routeParams as $key => $value) {
            if(preg_match('/^{\w+}$/', $value)){
                $params[] = $requestParams[$key];
            }
        }
        return $params;
    }

    public function run()
    {
        $this->map();
    }

}




