<?php

/*

Gonzalo Sinnott Segura

*/

class DBConnection
{
    private static $DBAccessObject;
    private $PDOObject;
 
    private function __construct()
    {
        try {
            $servername = "localhost";
            $dbname = "clase06";

            $this->PDOObject = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", 'root', '', array(PDO::ATTR_EMULATE_PREPARES => false,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $this->PDOObject->exec("SET CHARACTER SET utf8");
            } 
        catch (PDOException $e) { 
            print "Error!: " . $e->getMessage(); 
            die();
        }
    }
 
    public function ReturnQuery($sql)
    { 
        return $this->PDOObject->prepare($sql); 
    }
    
     public function ReturnLastInsertedId()
    { 
        return $this->PDOObject->lastInsertId(); 
    }
 
    public static function NewDBAccessObject()
    { 
        if (!isset(self::$DBAccessObject)) {          
            self::$DBAccessObject = new DBConnection(); 
        } 
        return self::$DBAccessObject;        
    }
 
 
     // Evita que el objeto se pueda clonar
    public function __clone()
    { 
        trigger_error('La clonación de este objeto no está permitida', E_USER_ERROR); 
    }
}

?>