<?php

/*

Gonzalo Sinnott Segura

*/

include_once "userSQLService.php";

class UserModel{

    public $id;
    public $name;
    public $surname;
    public $pass;
    public $mail;
    public $location;
    public $registerDate;

    public function __construct($name, $surname, $pass, $mail, $location)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->pass = $pass;
        $this->mail = $mail;
        $this->location = $location;
        $this->registerDate = date("Y-m-d H:i:s");
    }


    public function Register(){
        return userSQLService::InsertUser($this->name, $this->surname, $this->pass, $this->mail, $this->location, $this->registerDate);
    }

    public function Login(){
        return userSQLService::LoginUser($this->mail, $this->pass);        
    }

    public function List(){
        return userSQLService::ListUsers();
    }
}

?>
