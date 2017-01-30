<?php

namespace vendor\core;

use vendor\core\Config;

class DataBase
{
    private $username = null, $password = null, $dsn = null;

    public $database;

    public $errors;

    private static $dbInstance = null;

    public function __construct()
    {
        $config = Config::get('db');

        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->dsn = 'mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'];

        try {
            $this->database = new \PDO($this->dsn, $this->username, $this->password);
            $this->database->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public static function connect(){
        if(!isset(self::$dbInstance)) {
            self::$dbInstance = new self();
        }

        return self::$dbInstance;
    }

    private function __clone(){} // prevent cloning

    private function __wakeup(){} // prevent unserialization


}
