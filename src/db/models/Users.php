<?php

namespace db\models {
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once $root . '/lib/orm/OrmModel.php';
    require_once $root . '/lib/orm/Column.php';
    require_once $root . '/lib/orm/Query.php';

    use lib\orm\Column;
    use lib\orm\OrmModel;
    use lib\orm\Query;

    class Users extends OrmModel
    {
        public function __construct()
        {
            $this->table = 'users';
            $this->columns = [
                new Column('id', 'int', 11, false, true, true),
                new Column('username', 'varchar', 255, false, false, false),
                new Column('password', 'varchar', 255, false, false, false),
                new Column('email', 'varchar', 255, false, false, false),
                new Column('role', 'int', 1, false, false, false),
            ];

            parent::__construct();
        }

        public function findByEmailOrUsername(string $email): Users | null
        {
            $query = new Query($this);
            $result = $query
                ->where('email', $email)
                ->orWhere('username', $email)
                ->get();

            if (count($result) === 0) {
                return null;
            }

            return $result->first();
        }
        
        public function getById($id): Users | null
        {
            $query = new Query($this);
            $user = $query
                ->where('id', $id)
                ->get();
            
            if (count($user) === 0) {
                return null;
            }
            
            return $user->first();
        }
    }
}