<?php

use app\core\QueryBuilder;
use app\core\Router;

Router::get("/home/{username}", "HomeController@index@s");

Router::get("/user/{username}/{password}", function ($username, $pass) {
    echo "Day la trang user<br>";
    echo "username: " . $username . '<br>';
});

Router::any("/list", function () {
    $query = QueryBuilder::table('a')->select("col1", "col2")->distinct()
        ->join("b", "b.1", "=", "a.1")->leftJoin("c", "c.1", "=", "a.1")
        ->where("a.2", "=", 2)
        ->orWhere("a.3", "<", 100)
        ->groupBy("cot1", "cot2")
        ->having("cot1", "=", 50)
        ->having("cot2", "=", 100)
        ->orHaving("cot3", ">", 60)
        ->orderBy("cot1", "ASC")
        ->orderBy("cot2", "DESC")
        ->limit(10)
        ->offset(5)
        ->get();
    echo '<pre>';
    print_r($query);
    echo "Day la trang list";
});

Router::get("*", function () {
    echo "404 not found";
});
