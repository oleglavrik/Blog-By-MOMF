<?php
/**
 * Test class for example:
 */
namespace app\models;

use vendor\core\Models\BaseModel;

class User extends BaseModel
{
    public function getUsers()
    {
        $dbh = $this->db->prepare("SELECT * FROM user");
        $dbh->execute();

        if ($dbh->rowCount()) {
            return $dbh->fetchAll(\PDO::FETCH_ASSOC);
        }
    }
}