<?php

namespace app\core;

use app\core\AppException;

class Router
{
    private static $routers = array();
    private static $basePath;

    public function __construct($basePath)
    {
        self::$basePath = $basePath;
    }

    public function getRequestURL()
    {
        $uri = $_SERVER["REQUEST_URI"] ?: "/";
        $endPoint = str_replace(self::$basePath, "", $uri);
        return !empty($endPoint) ? $endPoint : "/";
    }

    private static function addRouter($method, $url, $action)
    {
        self::$routers[] = array($method, $url, $action);
    }

    public static function get($url, $action)
    {
        self::addRouter("GET", $url, $action);
    }

    public static function post($url, $action)
    {
        self::addRouter("POST", $url, $action);
    }

    public static function any($url, $action)
    {
        self::addRouter("GET|POST", $url, $action);
    }

    private function getRequestMethod()
    {
        return $_SERVER["REQUEST_METHOD"] ?: "GET";
    }

    private static function map()
    {
        $requestURL = self::getRequestURL();
        $requestMethod = self::getRequestMethod();
        $params = array();
        foreach (self::$routers as $router) {
            $matchRoute = false;
            list($method, $url, $action) = $router;

            if ($url == "*") {
                $matchRoute = true;
            } elseif (strpos($method, $requestMethod) !== false) {
                if (strpos($url, "{") !== false && strpos($url, "}") !== false) {
                    $params = self::getParamFromURL($url, $requestURL);
                    if (empty($params)) {
                        continue;
                    }
                    $matchRoute = true;
                }
                if (strtolower($requestURL) == strtolower($url)) {
                    $matchRoute = true;
                }
            }
            if ($matchRoute) {
                if (is_callable($action)) {
                    call_user_func_array($action, $params);
                    return;
                } elseif (is_string($action)) {
                    self::compileRoute($action, $params);
                    return;
                }
            }
        }
    }

    private static function compileRoute($action, $params)
    {
        $controlMix = explode("@", $action);
        if (count($controlMix) != 2) {
            throw new AppException("route error");
        }
        list($class, $method) = $controlMix;
        $classNamespace = "app\\controllers\\{$class}";
        if (class_exists($classNamespace)) {
            $obj = new $classNamespace;
            if (method_exists($classNamespace, $method)) {
                \App::setController($class);
                \App::setAction($method);
                call_user_func_array(array($obj, $method), $params);
            } else {
                throw new AppException("method {$method} not found");
            }
        } else {
            throw new AppException("class {$classNamespace} not found");
        }
    }

    private static function getParamFromURL($url, $requestURL)
    {
        $params = array();
        $routeParams = explode("/", $url);
        $requestParams = explode("/", $requestURL);
        if (count($routeParams) != count($requestParams)) {
            return $params;
        }
        $routeParams = explode("/", $url);
        $requestParams = explode("/", $requestURL);

        foreach ($routeParams as $key => $rp) {
            if (preg_match('/^{\w+}$/', $rp)) {
                $params[] = $requestParams[$key];
            } elseif ($rp != $requestParams[$key]) {
                return array();
            }
        }
        return $params;
    }

    public static function run()
    {
        self::map();
    }

}
