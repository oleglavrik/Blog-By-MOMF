<?php

namespace app\controllers;

use vendor\core\Controller;

class DefaultController extends Controller
{
    public function error404Action()
    {
        echo $this->twig->render('exception/error404.twig');
    }
}