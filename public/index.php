<?php

require_once("./../app/core/App.php");
require_once("./../app/core/Autoload.php");

$config = require_once("./../app/config/main.php");

$app = new App($config);
$app->run();

