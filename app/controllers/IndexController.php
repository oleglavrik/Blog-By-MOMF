<?php

namespace app\controllers;

use vendor\core\Controller;
use app\models\User;

class IndexController extends Controller
{
    public function indexAction()
    {
        // get users
        $users = new User();
        $users = $users->getUsers();

        echo $this->twig->render(
            'index/index.twig',
            [
                'users' => $users
            ]
        );

        return true;
    }

    public function articleAction()
    {
        echo $this->twig->render('index/article.twig');

        return true;
    }

    public function viewAction($id)
    {
        echo $this->twig->render(
            'index/view.twig',
            array('id' => $id)
        );

        return true;
    }

}