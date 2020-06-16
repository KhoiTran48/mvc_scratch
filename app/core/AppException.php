<?php

namespace app\core;

use \Exception;

class AppException extends Exception
{
    public function __construct($message, $code = null)
    {
        set_exception_handler([$this, "error_handle"]);
        parent::__construct($message, $code);
    }

    public function error_handle($e)
    {
        echo "<h1>{$e->getCode()} => {$e->getMessage()}</h1>";
        echo "<h2>{$e->getFile()} => {$e->getLine()}</h2>";
        echo "<p>{$e->getTraceAsString()}</p>";
    }
}
