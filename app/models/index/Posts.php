<?php
namespace app\models\index;

use app\controllers\ExceptionController;
use vendor\core\Config;
use vendor\core\Models\BaseModel;

class Posts extends BaseModel
{
    const SQL_GET_POSTS = '
        SELECT 
            id, title, description, content, createdAt, updatedAt, author 
        FROM 
            post
        ORDER BY id DESC LIMIT :position, :post_per_page  
    ';

    const SQL_GET_POST_BY_ID = '
        SELECT 
            id, title, description, content, createdAt, updatedAt, author 
        FROM 
            post
        WHERE 
            id = :id
    ';

    const SQL_INSERT_POST = '
        INSERT 
            INTO post (title, description, content, createdAt, updatedAt, author)
        VALUES(:title, :description, :content, :createdAt, :updatedAt, :author)
    ';

    /**
     * @return array
     */
    public function getPosts($page = 1)
    {
        $posts_per_page = new Config();
        $posts_per_page = $posts_per_page->get('config');

        $position = (($page - 1) * $posts_per_page['posts_per_page']);

        try {
            $dbh = $this->db->prepare(self::SQL_GET_POSTS);
            $dbh->bindParam(':position', $position, \PDO::PARAM_INT);
            $dbh->bindParam(':post_per_page', $posts_per_page['posts_per_page'], \PDO::PARAM_INT);
            $dbh->execute();

            if ($dbh->rowCount()) {
                return $dbh->fetchAll(\PDO::FETCH_ASSOC);
            }
        } catch (\PDOException $e) {
            $exceptionController = new ExceptionController();
            $exceptionController->modelException($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getPostByID($id)
    {
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
            $exceptionController = new ExceptionController();
            $exceptionController->modelException($e->getMessage());
        }
    }

    /**
     * @param array $data
     */
    public function addPost(array $data){
        try {
            $dbh = $this->db->prepare(self::SQL_INSERT_POST);
            $dbh->bindValue(':title', $data['title'], \PDO::PARAM_STR);
            $dbh->bindValue(':description', $data['description'], \PDO::PARAM_STR);
            $dbh->bindValue(':content', $data['content'], \PDO::PARAM_STR);
            $dbh->bindValue(':author', $data['author'], \PDO::PARAM_STR);
            $dbh->bindValue(':createdAt', $data['createdAt'], \PDO::PARAM_STR);
            $dbh->bindValue(':updatedAt', $data['updatedAt'], \PDO::PARAM_STR);
            $dbh->execute();
        } catch (\PDOException $e) {
            $exceptionController = new ExceptionController();
            $exceptionController->modelException($e->getMessage());
        }

    }
}