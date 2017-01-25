<?php

namespace vendor\core;


class View
{

    private static $_view;

    public $view;

    public $config = [];

    static private $layout;

    public $view_path = '';

    /**
     * Get general config
     */
    public function __construct()
    {
        $this->config = require_once ROOT_DIR . 'app/config/config.php';
    }

    /**
     * @param null $layout
     * @return View
     */
    public function getView($layout = null){

        if (null === self::$_view) {
            self::$_view = new self();
        }

        // get current layout
        if($layout === null){
            self::$layout = ROOT_DIR . 'app/views/layouts/' . $this->config['default-layout'] . '.php';
        }else{
            self::$layout = ROOT_DIR . 'app/views/layouts/' . $layout . '.php';
        }

        return self::$_view;
    }

    public function render($folder, $view, $attr = null){
        if(!empty(ob_get_contents())){
            ob_end_clean();
        }
        ob_start();

        $this->view_path = ROOT_DIR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $folder  . DIRECTORY_SEPARATOR . $view . '.php';

        if(is_file($this->view_path)){
            $this->view_folder = $folder;
            if(isset($attr) && is_array($attr)){
                $this->attr = $attr;
                extract($attr, EXTR_OVERWRITE);
            }

            include $this->view_path;
        } else {
            throw new \Exception('File: ' . $this->view_path . ' not exist!');
        }

        $this->view =  ob_get_clean();

        require_once self::$layout; //подключение layout
    }
}