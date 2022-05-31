<?php

/*

Gonzalo Sinnott Segura

*/
include_once "dbConnection.php";

class userSQLService
{

    public static function InsertUser($name, $surname, $pass, $mail, $location, $registerDate)
    {
        $dbObject = DBConnection::NewDBAccessObject();
        $query = $dbObject->ReturnQuery("INSERT INTO users (name,surname,pass,mail,location,registerDate)
                                                         VALUES(:name,:surname,:pass,:mail,:location,:registerDate)");
        $query->bindValue(':name', $name, PDO::PARAM_STR);
        $query->bindValue(':surname', $surname, PDO::PARAM_STR);
        $query->bindValue(':pass', $pass, PDO::PARAM_STR);
        $query->bindValue(':mail', $mail, PDO::PARAM_STR);
        $query->bindValue(':location', $location, PDO::PARAM_STR);
        $query->bindValue(':registerDate', $registerDate, PDO::PARAM_STR);
        $query->execute();
        return $dbObject->ReturnLastInsertedId();
    }

    public static function LoginUser($mail, $pass)
    {
        $dbObject = DBConnection::NewDBAccessObject();
        $query = $dbObject->ReturnQuery("SELECT * FROM users WHERE mail = :mail AND pass = :pass");
        $query->bindValue(':mail', $mail, PDO::PARAM_STR);
        $query->bindValue(':pass', $pass, PDO::PARAM_STR);
        $query->execute();
        $return = $query->fetch(PDO::FETCH_OBJ);
        return $return;
    }

    public static function ListUsers()
    {
        $dbObject = DBConnection::NewDBAccessObject();
        $query = $dbObject->ReturnQuery("SELECT * FROM users");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>