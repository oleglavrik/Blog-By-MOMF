<?php

namespace vendor\core;

use vendor\core\Interfaces\IController;

class Controller implements IController
{
    protected $twig;

    const VIEW_DIR = __DIR__ . '/../../app/views';

    public function __construct()
    {
        $loader = new \Twig_Loader_Filesystem(self::VIEW_DIR);
        $this->twig = new \Twig_Environment($loader);
    }

    public function error404() {
        echo $this->twig->render('exception/error404.twig');

        return true;
    }

    public function redirectToRoute($route, $statusCode = 303){
        header('Location: ' . $route, true, $statusCode);
        die();
    }

}