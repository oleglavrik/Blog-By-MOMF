<?php

namespace app\models\index;

use vendor\core\Models\BaseModel;

class Post extends BaseModel
{
    const SQL_GET_POST_BY_ID = '
        SELECT 
            id, title, description, content, createdAt, updatedAt, author 
        FROM 
            post
        WHERE 
            id = :id
        ';

    public function getPostByID($id){
        $dbh = $this->db->prepare(self::SQL_GET_POST_BY_ID);
        $dbh->execute([':id' => (int)$id]);

        try {
            if ($dbh->rowCount()) {
                // get post data
                $post = $dbh->fetch(\PDO::FETCH_ASSOC);
                // divide content text to paragraph
                $post['content'] = preg_split('/\\r\\n?|\\n/', $post['content']);

                return $post;
            }
        } catch (\PDOException $e) {
            echo ($e->getMessage());
        }
    }
}