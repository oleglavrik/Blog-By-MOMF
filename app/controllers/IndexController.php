<?php

namespace app\controllers;

use vendor\core\Controller;
use app\models\User;

class IndexController extends Controller
{
    public function indexAction()
    {
        $users = new User();
        $users = $users->getUsers();

        echo $this->twig->render('index/index.twig');
    }

    public function articleAction()
    {
        echo $this->twig->render('index/article.twig');
    }

}