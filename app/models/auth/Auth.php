<?php

namespace app\models\auth;

use vendor\core\Models\BaseModel;

class Auth extends BaseModel
{
    const SQL_REGISTER_USER = '
        INSERT
            INTO users (username, password, joined)
        VALUES (:username, :password, :joined)
    ';

    public function registerUser(array $data) {
        try {
            $dbh = $this->db->prepare(self::SQL_REGISTER_USER);
            $dbh->bindValue(':username', $data['username'], \PDO::PARAM_STR);
            $dbh->bindValue(':password', $data['password'], \PDO::PARAM_STR);
            $dbh->bindValue(':joined', $data['joined'], \PDO::PARAM_STR);
            $dbh->execute();
        } catch (\PDOException $e) {
            echo ($e->getMessage());
        }
    }
}