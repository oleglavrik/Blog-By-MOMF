<?php

namespace vendor\core;

class Router
{
    const CONTROLLERS_PATHWAY = 'app\\controllers\\';

    public function run()
    {
        // get request
        $route = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        $routing = require_once ROOT_DIR . 'app/config/pathways.php';

        if(isset($routing[$route])){
            $controller = self::CONTROLLERS_PATHWAY. $routing[$route]['controller'] . 'Controller';
            // get controller
            $controllerObj = new $controller();
            // get action
            $action = $routing[$route]['action'] . 'Action';
            // init current action from current controller
            $controllerObj->$action();
        } else {
            (new View)->getView('layout')->render('service', 'error', array('message' => 'The pathway not found!'));
        }
    }
}