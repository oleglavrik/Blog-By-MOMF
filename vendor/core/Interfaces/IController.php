<?php

namespace vendor\core\Interfaces;

use vendor\core\Request;

interface IController
{
    public function redirectToRoute($route, $referrerClear = false, $statusCode = 303);

    public function securityAuth(Request $request);
}