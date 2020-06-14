<?php

require_once("./../app/core/App.php");
require_once("./../app/core/Autoload.php");

$config = require_once("./../app/config/main.php");
App::setConfig($config);

$app = new App();
$app->run();

