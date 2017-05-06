<?php

namespace app\models\auth;

use vendor\core\Models\BaseModel;

class Sessions extends BaseModel
{
    const SQL_SET_AUTH_SESSION = '
        INSERT
            INTO sessions (user_id, ip, user_agent, time, hash)
        VALUES (:user_id, :ip, :user_agent, :time, :hash)
    ';


    public function setSession(array $data) {
        try {
            $dbh = $this->db->prepare(self::SQL_SET_AUTH_SESSION);
            $dbh->bindValue(':user_id', $data['user_id'], \PDO::PARAM_INT);
            $dbh->bindValue(':ip', $data['ip'], \PDO::PARAM_STR);
            $dbh->bindValue(':user_agent', $data['user_agent'], \PDO::PARAM_STR);
            $dbh->bindValue(':time', $data['time'], \PDO::PARAM_STR);
            $dbh->bindValue(':hash', $data['hash'], \PDO::PARAM_STR);

            $result = $dbh->execute();
            return $result;
        }catch (\PDOException $e) {
            die($e->getMessage());
        }
    }
}