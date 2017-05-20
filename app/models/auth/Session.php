<?php

namespace app\models\auth;

use vendor\core\Models\BaseModel;

class Session extends BaseModel
{
    const SQL_SET_USER_SESSION = '
        INSERT INTO sessions
            (user_id, ip, user_agent, time, hash) 
        VALUES 
            (:user_id, :ip, :user_agent, :time, :hash)
            
    ';

    const SQL_GET_USER_SESSION_BY_HASH = '
        SELECT 
            id, user_id, ip, user_agent, time, hash
        FROM
            sessions
        WHERE hash = :hash LIMIT 1
        
    ';

    const SQL_DELETE_USER_SESSION_BY_ID = '
        DELETE FROM sessions
        WHERE id = :id
    ';

    const SQL_DELETE_USER_SESSION_BY_USER_ID = '
        DELETE FROM sessions
        WHERE user_id = :user_id
    ';

    const SQL_GET_USER_SESSION_BY_USER_ID = '
        SELECT 
            id, user_id, ip, user_agent, time, hash
        FROM
            sessions
        WHERE user_id = :user_id LIMIT 1
    ';

    const SQL_FIND_USER_SESSION_BY_USER_DATA = '
        SELECT 
            COUNT(*) as count
        FROM 
            sessions
        WHERE 
            user_id = :user_id && ip = :ip && user_agent = :user_agent && hash = :hash
        LIMIT 1 
    ';

    public function setSession(array $data) {
        try {
            $dbh = $this->db->prepare(self::SQL_SET_USER_SESSION);
            $dbh->bindValue(':user_id', $data['user_id'], \PDO::PARAM_INT);
            $dbh->bindValue(':ip', $data['ip'], \PDO::PARAM_STR);
            $dbh->bindValue(':user_agent', $data['user_agent'], \PDO::PARAM_STR);
            $dbh->bindValue(':time', $data['time'], \PDO::PARAM_STR);
            $dbh->bindValue(':hash', $data['hash'], \PDO::PARAM_STR);

            return $dbh->execute();
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getUserSessionByHash($hash) {
        try {
            $dbh = $this->db->prepare(self::SQL_GET_USER_SESSION_BY_HASH);
            $dbh->bindValue(':hash', $hash, \PDO::PARAM_STR);
            $dbh->execute();

            return $dbh->fetchAll(\PDO::FETCH_ASSOC)['0'];
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }

    public function unsetSessionById($id) {
        try {
            $dbh = $this->db->prepare(self::SQL_DELETE_USER_SESSION_BY_ID);
            $dbh->bindValue('id', $id, \PDO::PARAM_INT);

            return $dbh->execute();
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getUserSessionByUserID($user_id) {
        try{
            $dbh = $this->db->prepare(self::SQL_GET_USER_SESSION_BY_USER_ID);
            $dbh->bindValue('user_id', $user_id, \PDO::PARAM_INT);
            $dbh->execute();

            return $dbh->fetchAll(\PDO::FETCH_ASSOC)['0'];
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }

    public function findActiveSessionByUserData(array $data) {
        try {
            $dbh = $this->db->prepare(self::SQL_FIND_USER_SESSION_BY_USER_DATA);
            $dbh->bindValue('user_id', $data['user_id'], \PDO::PARAM_INT);
            $dbh->bindValue('ip', $data['ip'], \PDO::PARAM_STR);
            $dbh->bindValue('user_agent', $data['user_agent'], \PDO::PARAM_STR);
            $dbh->bindValue('hash', $data['hash'], \PDO::PARAM_STR);

            $dbh->execute();

            return $dbh->fetchAll(\PDO::FETCH_ASSOC)['0'];
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }

    public function deleteUserSessionByUserID($userID) {
        try {
            $dbh = $this->db->prepare(self::SQL_DELETE_USER_SESSION_BY_USER_ID);
            $dbh->bindValue('user_id', $userID, \PDO::PARAM_INT);

            return $dbh->execute();
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }
}