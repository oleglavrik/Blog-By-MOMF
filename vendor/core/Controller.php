<?php

namespace vendor\core;

class Controller
{
    private $calledClass;

    public function __construct()
    {
        $path = get_class($this);
        $this->called_class = str_replace('controller', '', substr(strrchr(strtolower($path), "\\"), 1));
    }


    public function render($view, $attributes = null)
    {
        if(is_object($attributes)){
            $attributes = $attributes->fields;
        }
        try{
            (new View)->getView(isset($this->layout)?$this->layout:null)->render($this->called_class, $view, $attributes);
        } catch(\Exception $e){
            (new View)->getView('layout')->render('service', '404', $attributes);
        }
    }
}