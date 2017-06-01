<?php

namespace app\controllers;

use app\models\index\Posts;
use vendor\core\Config;
use vendor\core\Controller;
use vendor\core\Navigation;
use vendor\core\Request;

class IndexController extends Controller
{
    public function indexAction()
    {
        // get posts
        $posts = new Posts();
        $posts = $posts->getPosts();

        echo $this->twig->render(
            'index/index.twig',
            [
                'posts' => $posts
            ]
        );

        return true;
    }

    public function showAction($id)
    {
        // get post by id
        $post = new Posts();
        $post = $post->getPostByID($id);

        echo $this->twig->render(
            'index/show.twig',
            [
                'post' => $post
            ]
        );

        return true;
    }

    public function ajaxAction()
    {
        $page = (int)$_POST["page"];

        // get posts
        $posts = new Posts();
        $posts = $posts->getPosts($page);

        if (!empty($posts))
            foreach ($posts as $post) {
                $date = date('l d, Y', strtotime($post['createdAt']));
                echo <<<TEXT
            <div class="post-preview">
                <a href="/post/show/$post[id]">
                    <h2 class="post-title">
                        $post[title]
                    </h2>
                    <h3 class="post-subtitle">
                        $post[description]
                    </h3>
                </a>
                <p class="post-meta">Posted by <a href="#">$post[author]</a> on $date </p>
            </div>
            <hr>
TEXT;

            }
        else
            echo null;

        return true;
    }
}