<?php

class Autoload
{
    private $rootPath;
    public function __construct($rootPath)
    {
        $this->rootPath = $rootPath;
        spl_autoload_register(array($this, "autoload"));
        $this->autoLoadFile();
    }

    private function autoload($class)
    {
        $className = end(explode("\\", $class));
        $pathName = str_replace($className, "", $class);

        $filePath = $this->rootPath . "\\" . $pathName . $className . ".php";
        $filePath = str_replace("\\", "/", $filePath);
        if (file_exists($filePath)) {
            require_once $filePath;
        }
    }

    private function autoLoadFile()
    {
        foreach ($this->defaultFileLoad() as $file) {
            require_once $this->rootPath . "/" . $file;
        }
    }

    private function defaultFileLoad()
    {
        return array(
            // "app/core/Router.php",
            "app/routers.php",
        );
    }

}
