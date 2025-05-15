<?php

class DB
{
    private static $db = null;

    private static function getDB()
    {
        // if(is_null(self::$db)) {
        //     self::$db = new \PDO(
        //         "mysql:host=www.yydhsoft.com; dbname=work01; charset=utf8mb4;",
        //         "work01",
        //         "1234"
        //     );
        // }

        if(is_null(self::$db)) {
                self::$db = new \PDO(
                    "mysql:host=skillscedular.mysql.database.azure.com; dbname=work01; charset=utf8mb4;",
                    "work01@skillscedular",
                    "neneong17894!"
                );
            }
        return self::$db;
    }


    public static function execute($sql , $data = [])
    {
        $q = self::getDB()->prepare($sql);
        $q->execute($data);
        return $q;
    }
    
    public static function fetch($sql , $data =[] , $mode = \PDO::FETCH_OBJ) {
        return self::execute($sql , $data)->fetch($mode);
    }
    public static function fetchAll($sql , $data =[] , $mode = \PDO::FETCH_OBJ) {
        return self::execute($sql , $data)->fetchAll($mode);
    }
    
    

}