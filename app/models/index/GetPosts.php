<?php

namespace app\models\index;

use vendor\core\Models\BaseModel;

class GetPosts extends BaseModel
{
    const SQL_GET_ARTICLES = '
        SELECT 
            id, title, description, content, createdAt, updatedAt, author 
        FROM 
            post
        ';

    /**
     * @return array
     */
    public function get(){
        $dbh = $this->db->prepare(self::SQL_GET_ARTICLES);
        $dbh->execute();

        if ($dbh->rowCount()) {
            return $dbh->fetchAll(\PDO::FETCH_ASSOC);
        }
    }
}