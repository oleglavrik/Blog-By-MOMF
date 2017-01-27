<?php

namespace app\controllers;

use vendor\core\Controller;

class DefaultController extends Controller
{
    public function error404Action()
    {
        echo $this->twig->render('Exception/error404.twig');
    }
}