<?php

namespace vendor\core;

use app\controllers\ExceptionController;
use app\controllers\IndexController;
use vendor\core\Config;

class Router
{
    const CONTROLLERS_PATHWAY = 'app\\controllers\\';

    private $routes;

    public function __construct(){
        $this->routes = Config::get('routes');
    }

    private function getURI(){
        if(!empty($_SERVER['REQUEST_URI'])){
            $uri = trim($_SERVER['REQUEST_URI'], '/');

            if($uri === ''){
                $uri = '/';
            }

            return $uri;
        }
    }

    public function run(){
        // get url
        $uri = $this->getURI();

        try {
            $exception404 = true;

            foreach ($this->routes as $uriPattern => $path) {
                if(preg_match("#^$uriPattern$#", $uri)) {
                    $internalRoute = preg_replace("~$uriPattern~", $path, $uri);
                    // get segments
                    $segments = explode('/', $internalRoute);

                    // get controller name Example: app\controllers\IndexController
                    $controllerName = self::CONTROLLERS_PATHWAY . ucfirst(array_shift($segments) . 'Controller');

                    // get action name / Example: indexAction
                    $actionName = array_shift($segments) . 'Action';

                    // get parameters
                    $parameters = $segments;

                    // create controller obj
                    $controllerObject = new $controllerName;

                    // run method
                    $result = call_user_func_array(array($controllerObject, $actionName), $parameters);

                    // if route found, break foreach
                    if($result != null){
                        $exception404 = false;
                        break;
                    }
                }
            }

            if($exception404) {
                throw new \Exception();
            }

        } catch (\Exception $e) {
            $exceptionController = new ExceptionController();
            $exceptionController->error404();
        }
    }

}


