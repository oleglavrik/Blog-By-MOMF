<?php

namespace app\controllers;

use vendor\core\Config;
use vendor\core\Controller;
use app\models\index\Posts;
use vendor\core\Request;
use vendor\core\FlashMessages;

class AdminController extends Controller
{

    /**
     * @return bool
     */
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

    /**
     * @return bool
     */
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
                                <li><a href="/post/edit/$post[id]">Edit</a></li>
                                <li><a href="/post/delete/$post[id]">Delete</a></li>
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