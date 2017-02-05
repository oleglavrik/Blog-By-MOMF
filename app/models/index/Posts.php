<?php

namespace app\models\index;

use vendor\core\Models\BaseModel;

class Posts extends BaseModel
{
    const SQL_GET_POSTS = '
        SELECT 
            id, title, description, content, createdAt, updatedAt, author 
        FROM 
            post
        ';

    /**
     * @return array
     */
    public function get(){
        try {
            $dbh = $this->db->prepare(self::SQL_GET_POSTS);
            $dbh->execute();

            if ($dbh->rowCount()) {
                return $dbh->fetchAll(\PDO::FETCH_ASSOC);
            }
        } catch (\PDOException $e) {
            echo ($e->getMessage());
        }
    }
}