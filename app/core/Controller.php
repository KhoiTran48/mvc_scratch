<?php

namespace app\core;

use app\core\Registry;

class Controller
{
    private $layout = null;

    public function __construct()
    {
        $this->layout = Registry::getInstance()->config["layout"];
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function redirect($url, $isEnd = true, $responseCode = 302)
    {
        header("Location:" . $url, true, $responseCode);
        if ($isEnd) {
            die();
        }
    }

    public function render($view, $data = null)
    {
        $rootPath = Registry::getInstance()->config["rootPath"];
        $content = $this->getViewContent($view, $data);
        if (is_array($data)) {
            extract($data, EXTR_PREFIX_SAME, "data");
        }
        if ($this->layout != null) {
            $layoutPath = "{$rootPath}/app/views/{$this->layout}.php";
            if (file_exists($layoutPath)) {
                require_once $layoutPath;
            }
        }
    }

    public function getViewContent($view, $data)
    {
        $controller = \App::getController();
        $folderView = strtolower(str_replace("Controller", "", $controller));
        $rootPath = Registry::getInstance()->config["rootPath"];

        if (is_array($data)) {
            extract($data, EXTR_PREFIX_SAME, "data");
        }
        $viewPath = "{$rootPath}/app/views/{$folderView}/{$view}.php";
        if (file_exists($viewPath)) {
            ob_start();
            require_once $viewPath;
            return ob_get_clean();
        }
    }

    public function renderPartial($view, $data = null)
    {
        $rootPath = Registry::getInstance()->config["rootPath"];
        if (is_array($data)) {
            extract($data, EXTR_PREFIX_SAME, "data");
        }
        $viewPath = "{$rootPath}/app/views/{$view}.php";
        if (file_exists($viewPath)) {
            require_once $viewPath;
        }
    }

}
