<?php

namespace app\controllers;

use app\models\index\GetPosts;
use vendor\core\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        // get posts
        $posts = new GetPosts();
        $posts = $posts->get();

        echo $this->twig->render(
            'index/index.twig',
            [
                'posts' => $posts
            ]
        );

        return true;
    }
}