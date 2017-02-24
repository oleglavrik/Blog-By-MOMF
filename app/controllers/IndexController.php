<?php

namespace app\controllers;

use app\models\index\Posts;
use vendor\core\Controller;
use vendor\valitron\src\Valitron;

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

    public function addAction()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // validator for add new post form
            $validator = new Valitron\Validator($_POST);
            $rules = [
                'required' => [
                    ['title'],
                    ['description'],
                    ['content'],
                    ['author']
                ],
                'lengthMin' => [
                    ['title', 5],
                ]
            ];
            $validator->rules($rules);

            if($validator->validate()){
                // get data
                $data['title'] = $_POST['title'];
                $data['description'] = $_POST['description'];
                $data['content'] = $_POST['content'];
                $data['author'] = $_POST['author'];
                $data['createdAt'] = date('Y-m-d H:i:s');
                $data['updatedAt'] = date('Y-m-d H:i:s');

                //insert new post
                $post = new Posts();
                $post->addPost($data);

                // redirect to home
                $this->redirectToRoute('/');
            }else {

                echo $this->twig->render(
                    'index/add.twig',
                    [
                        'errors' => $validator->errors()
                    ]
                );

                return true;
            }
        }

        echo $this->twig->render(
            'index/add.twig'
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