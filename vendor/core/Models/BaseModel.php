<?php

namespace vendor\core\Models;

use vendor\core\DataBase;

abstract class BaseModel
{
    protected $db;

    public function __construct()
    {
        $this->db = DataBase::connect()->database;
    }
}