<?php

namespace vendor\core;

define('VIEW_DIR', realpath(__DIR__ . '/../../app/views') . '/');

class Controller
{
    public $twig;

    public function __construct()
    {
        $loader = new \Twig_Loader_Filesystem(VIEW_DIR);
        $this->twig = new \Twig_Environment($loader);
    }

    public function error404()
    {
    }

    public function redirectToRoute($route, $statusCode = 303){
        header('Location: ' . $route, true, $statusCode);
        die();
    }

}