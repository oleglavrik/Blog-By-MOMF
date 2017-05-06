<?php

namespace app\models\auth;

use vendor\core\Models\BaseModel;

class User extends BaseModel
{
    const SQL_REGISTER_USER = '
        INSERT
            INTO users (username, password, joined)
        VALUES (:username, :password, :joined)
    ';

    const SQL_CHECK_USER_NAME = '
        SELECT 
            username FROM users 
        WHERE username = :username
            LIMIT 1
    ';

    const SQL_GET_USER_BY_USERNAME = '
        SELECT
            id, username, password, joined FROM users 
        WHERE username = :username
            LIMIT 1
    ';

    /**
     * @param array $data
     */
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

    /**
     * @param $userName
     * @return array
     */
    public function checkUserName($username) {
        try {
            $dbh = $this->db->prepare(self::SQL_CHECK_USER_NAME);
            $dbh->bindValue(':username', $username, \PDO::PARAM_STR);
            $dbh->execute();

            return $dbh->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            die($e->getMessage()); // need page with exceptions errors
        }
    }

    /**
     * @param $username
     * @return mixed
     */
    public function getUserByUserName($username) {
        try {
            $dbh = $this->db->prepare(self::SQL_GET_USER_BY_USERNAME);
            $dbh->bindValue(':username', $username, \PDO::PARAM_STR);
            $dbh->execute();

            return $dbh->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            die($e->getMessage()); // todo create page with exceptions
        }
    }

}
