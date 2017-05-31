<?php

namespace app\controllers;

use vendor\core\Controller;
use app\models\index\Posts;
use vendor\core\Request;

class AdminController extends Controller
{
    public function indexAction() {
        // security guard
        $this->securityAuth(new Request());

        // get posts
        $posts = new Posts();
        $posts = $posts->getPosts();


        echo $this->twig->render(
            'admin/index.twig',
            [
                'posts' => $posts
            ]
        );

        return true;

    }
}