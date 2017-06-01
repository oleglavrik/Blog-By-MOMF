<?php

namespace app\controllers;

use vendor\core\Config;
use vendor\core\Controller;
use app\models\index\Posts;
use vendor\core\Request;
use vendor\core\FlashMessages;

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

    public function addAction()
    {
        // security guard
        $this->securityAuth(new Request());

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // validator for add new post form
            $validator = new \Valitron\Validator($_POST);
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

                // insert new post
                $post = new Posts();
                $post->addPost($data);

                // set success message
                $message = new FlashMessages();
                $message->setMessage('Post successfully added.', 'success');

                // redirect to home
                $this->redirectToRoute('/admin');
            }else {
                // set error message
                $message = new FlashMessages();
                $message->setMessage('Oops something wrong.', 'danger');

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
            'admin/add.twig'
        );

        return true;
    }

    public function deleteAction($id) {
        // security guard
        $this->securityAuth(new Request());

        // get post
        $postObj = new Posts();
        $post = $postObj->getPostByID($id);

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(isset($_POST['post-delete'])) {
                if($postObj->deletePost($_POST['postID'])) {
                    /* Post successfully removed */
                    $message = new FlashMessages();
                    $message->setMessage('Post successfully deleted.', 'success');

                    $this->redirectToRoute('/admin');
                }
            }else {
                // cancel, redirect to admin index
                $this->redirectToRoute('/admin');
            }
        }

        echo $this->twig->render(
            'admin/delete.twig',
            ['post' => $post]
        );

        return true;
    }

    public function ajaxAction()
    {
        $page = (int)$_POST["page"];

        // get posts
        $posts = new Posts();
        $posts = $posts->getPosts($page);

        if (!empty($posts)) {

            foreach ($posts as $post) {
                echo <<<TEXT
                <tr>
                    <td>$post[title]</td>
                    <td>$post[author]</td>
                    <td>$post[createdAt]</td>
                    <td class="text-center">
                        <!-- Split button -->
                        <!-- Small button group -->
                        <div class="btn-group">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Actions <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#">Edit</a></li>
                                <li><a href="#">Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
TEXT;

            }
        }
        else {
            echo null;
        }

        return true;
    }
}