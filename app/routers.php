<?php

use app\core\Router;

Router::get("/home/{username}", "HomeController@index");

Router::get("/user/{username}/{password}", function ($username, $pass) {
    echo "Day la trang user<br>";
    echo "username: " . $username . '<br>';
});

Router::any("/list", function () {
    echo "Day la trang list";
});

Router::get("*", function () {
    echo "404 not found";
});
