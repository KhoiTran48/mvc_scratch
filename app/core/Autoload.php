<?php

class Autoload
{
    public function __construct()
    {
        spl_autoload_register(array($this, "autoload"));
    }

    private function autoload($class)
    {
        $className = end(explode("\\", $class));
        $pathName = str_replace($className, "", $class);

        $rootPath = App::getConfig()["rootPath"];
        $filePath = $rootPath . "\\" . $pathName . $className . ".php";
        $filePath = str_replace("\\", "/", $filePath);
        if(file_exists($filePath)){
            require_once($filePath);
        }
    }

}
