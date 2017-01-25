<?php

namespace app\controllers;

use vendor\core\Controller;
use vendor\core\Model;
use app\models\User;

class IndexController extends Controller
{
    public function indexAction()
    {
        $users = new User();
        $users = $users->getUsers();

        $this->render(
            'index',
            array('users' => $users)
        );
    }

    public function articleAction()
    {
        $this->render('article');
    }
}