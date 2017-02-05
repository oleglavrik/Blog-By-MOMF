<?php

namespace app\controllers;

use app\models\index\Post;
use app\models\index\Posts;
use vendor\core\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        // get posts
        $posts = new Posts();
        $posts = $posts->get();


        echo $this->twig->render(
            'index/index.twig',
            [
                'posts' => $posts
            ]
        );

        return true;
    }

    public function getContentArr($string) {
        $arr = [];

        foreach (explode("\n", $string) as $line) {
            if (trim($line)) {
                $arr[] += $arr[$line]  ;
            }
        }

        return $arr;
    }

    public function showAction($id) {

        $post = new Post();
        $post = $post->getPostByID($id);


        echo $this->twig->render(
            'index/show.twig',
            [
                'post' => $post
            ]
        );

        return true;
    }

    public function addAction(){
        // todo if implement addAction need use PDO->transactions in model
    }
}