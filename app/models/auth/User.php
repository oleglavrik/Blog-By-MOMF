<?php

namespace app\models\auth;

use vendor\core\Models\BaseModel;
use app\controllers\ExceptionController;

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

    const SQL_GET_USER_NAME_BY_USER_ID = '
        SELECT 
            username FROM users
        WHERE id = :id
            LIMIT 1
    ';

    /**
     * @param $id
     * @return mixed
     */
    public function getUserNameByUserID($id) {
        try {
            $dbh = $this->db->prepare(self::SQL_GET_USER_NAME_BY_USER_ID);
            $dbh->bindValue(':id', $id, \PDO::PARAM_INT);
            $dbh->execute();

            return $dbh->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $exceptionController = new ExceptionController();
            $exceptionController->modelException($e->getMessage());
        }
    }

    /**
     * @param array $data
     * @return bool
     */
    public function registerUser(array $data) {
        try {
            $dbh = $this->db->prepare(self::SQL_REGISTER_USER);
            $dbh->bindValue(':username', $data['username'], \PDO::PARAM_STR);
            $dbh->bindValue(':password', $data['password'], \PDO::PARAM_STR);
            $dbh->bindValue(':joined', $data['joined'], \PDO::PARAM_STR);

            return $dbh->execute();
        } catch (\PDOException $e) {
            $exceptionController = new ExceptionController();
            $exceptionController->modelException($e->getMessage());
        }
    }

    /**
     * @param $username
     * @return bool
     */
    public function checkUserName($username) {
        try {
            $dbh = $this->db->prepare(self::SQL_CHECK_USER_NAME);

            $dbh->bindValue(':username', $username, \PDO::PARAM_STR);
            $dbh->execute();

            $user = $dbh->fetchAll(\PDO::FETCH_ASSOC);

            if(empty($user))
                return true;
            else
                return false;
        } catch (\PDOException $e) {
            $exceptionController = new ExceptionController();
            $exceptionController->modelException($e->getMessage());
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
            $exceptionController = new ExceptionController();
            $exceptionController->modelException($e->getMessage());
        }
    }

}
