<?php

namespace app\controllers;

use vendor\core\Controller;

class AdminController extends Controller
{
    public function indexAction() {

        echo $this->twig->render(
            'admin/index.twig'
        );

        return true;

    }
}