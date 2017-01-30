<?php

namespace app\controllers;

use vendor\core\Controller;
use app\models\User;

class IndexController extends Controller
{
    public function indexAction()
    {
        echo $this->twig->render('index/index.twig');

        return true;
    }
}