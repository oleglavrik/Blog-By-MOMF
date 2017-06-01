<?php

namespace app\controllers;

use vendor\core\Config;
use vendor\core\Controller;
use app\models\index\Posts;
use vendor\core\Request;
use vendor\core\FlashMessages;

class PostController extends Controller
{
    /**
     * @return bool
     */
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

            if(isset($_POST['post-add'])) {
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
                    if($post->addPost($data)) {
                        // set success message
                        $message = new FlashMessages();
                        $message->setMessage('Post successfully added.', 'success');

                        // redirect to home
                        $this->redirectToRoute('/admin');
                    }
                }else {
                    // set error message
                    $message = new FlashMessages();
                    $message->setMessage('Oops something wrong.', 'danger');

                    echo $this->twig->render(
                        'post/add.twig',
                        [
                            'errors' => $validator->errors()
                        ]
                    );

                    return true;
                }

            } else {
                // cancel, redirect to admin index
                $this->redirectToRoute('/admin');
            }
        }

        echo $this->twig->render(
            'post/add.twig'
        );

        return true;
    }

    /**
     * @param $id
     * @return bool
     */
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
            'post/delete.twig',
            ['post' => $post]
        );

        return true;
    }

    /**
     * @param $id
     * @return bool
     */
    public function editAction($id) {
        // security guard
        $this->securityAuth(new Request());

        // get post
        $postObj = new Posts();
        $post = $postObj->getPostByID($id);

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // validator for add new post form
            $validator = new \Valitron\Validator($_POST);
            $rules = [
                'required' => [
                    ['title'],
                    ['description'],
                    ['content'],
                    ['author'],
                    ['createdAt'],
                    ['updatedAt'],
                ],
                'lengthMin' => [
                    ['title', 5],
                ]
            ];

            $validator->rules($rules);

            if($validator->validate()){
                // get data
                $data['id'] = $_POST['id'];
                $data['title'] = $_POST['title'];
                $data['description'] = $_POST['description'];
                $data['content'] = $_POST['content'];
                $data['author'] = $_POST['author'];
                $data['createdAt'] = $_POST['createdAt'];
                $data['updatedAt'] = $_POST['updatedAt'];

                if(isset($_POST['post-edit'])) {
                    if($postObj->updatePost($data)) {
                        /* Post successfully edited */
                        $message = new FlashMessages();
                        $message->setMessage('Post successfully edited.', 'success');

                        $this->redirectToRoute('/admin');
                    }
                } else {
                    $this->redirectToRoute('/admin');
                }
            }else {
                // set error message
                $message = new FlashMessages();
                $message->setMessage('Oops something wrong.', 'danger');

                echo $this->twig->render(
                    'post/edit.twig',
                    [
                        'errors' => $validator->errors(),
                        'post' => $post
                    ]
                );

                return true;
            }
        }

        echo $this->twig->render(
            'post/edit.twig',
            ['post' => $post]
        );

        return true;
    }
}