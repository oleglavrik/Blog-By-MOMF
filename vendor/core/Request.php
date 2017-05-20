<?php

namespace vendor\core;

class Request
{
    public $referrerRoute;

    private function getURI(){
        if(!empty($_SERVER['REQUEST_URI'])){
            $uri = trim($_SERVER['REQUEST_URI'], '/');

            if($uri === ''){
                $uri = '/';
            }

            return $uri;
        }
    }

    public function __construct()
    {
        $this->referrerRoute = $this->getURI();
    }


    public function setSessionReferrer() {
        $_SESSION['http_referrer'] = $this->referrerRoute;
    }
}