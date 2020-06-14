<?php

namespace app\controllers;

class HomeController
{
    public function index($username)
    {
        echo "welcome: " . $username;
    }
}


