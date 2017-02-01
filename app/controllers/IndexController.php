<?php

namespace app\controllers;

use vendor\core\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        echo $this->twig->render('index/index.twig');

        return true;
    }
}