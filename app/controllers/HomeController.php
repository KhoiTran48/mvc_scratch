<?php

namespace app\controllers;

use app\core\Controller;

class HomeController extends Controller
{
    public function index($username)
    {
        $this->render("index", array("name" => "Alice", "age" => 123));
    }
}
