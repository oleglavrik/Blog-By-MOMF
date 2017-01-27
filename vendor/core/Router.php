<?php

namespace vendor\core;

use vendor\core\Config;

class Router
{
    const CONTROLLERS_PATHWAY = 'app\\controllers\\';

    public function run()
    {
        // get request
        $route = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        $routing = Config::get('pathways');

        if(!isset($routing[$route])) {
            // set default controller
            $controller = self::CONTROLLERS_PATHWAY .  'DefaultController';

            // init current action from current controller
            $controllerObj = new $controller();
            $action = 'error404Action';

            $controllerObj->$action();
        } else {
            $controller = self::CONTROLLERS_PATHWAY. $routing[$route]['controller'] . 'Controller';
            // get controller
            $controllerObj = new $controller();
            $action = $routing[$route]['action'] . 'Action';

            // init current action from current controller
            $controllerObj->$action();
        }
    }
}