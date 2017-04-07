<?php

namespace vendor\core\Interfaces;


interface IController
{
    public function redirectToRoute($route, $statusCode = 303);

    public function error404();
}