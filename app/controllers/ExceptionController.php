<?php

namespace app\controllers;

use vendor\core\Controller;

class ExceptionController extends Controller
{
    public function error404() {
        echo $this->twig->render('exception/error404.twig');

        return true;
    }
}