<?php
/* 

Sinnott Segura Gonzalo

*/

class Usuario{
    
    //--- Attributes ---
    public $_id;
    public $_name;
    public $_surname;
    public $_password;
    public $_email;
    public $_registerDate;
    public $_photoName;

    public function __construct($id){
        $this->setId($id);        
    }

    public function setName($name){
        if (is_string($name) && !empty($name)) {
            $this->_name = $name;
        }
    }

    public function setSurname($surname){
        if (is_string($surname) && !empty($surname)) {
            $this->_surname = $surname;
        }
    }

    public function setPassword($password){
        if (is_string($password) && !empty($password)) {
            $this->_password = $password;
        }
    }

    public function setEmail($email){
        if (is_string($email) && !empty($email)) {
            $this->_email = $email;
        }
    }

    public function setId($id){
        if (is_int($id) && !empty($id)) {
            $this->_id = $id;
        }
    }

    public function setRegisterDate($registerDate){
        if (is_string($registerDate) && !empty($registerDate)) {
            $this->_registerDate = $registerDate;
        }
    }

    public function setPhotoName($photoName)
    {
        if (is_string($photoName) && !empty($photoName)) {
            $this->_photoName = $photoName;
        }
    }

    public function getName(){
        return $this->_name;
    }

    public function getSurname(){
        return $this->_surname;
    }

   public function getPassword(){
        return $this->_password;
    }

    public function getEmail(){
        return $this->_email;
    }

    public function getId(){
        return $this->_id;
    }

    public function getRegisterDate(){
        return $this->_registerDate;
    }

    public function getPhotoName(){
        return $this->_photoName;
    }


    public static function ReadJSON($filename="usuarios.json"):array{
        $users = array();
        try {
            if (file_exists($filename)) {                  
                $file = fopen($filename, "r");
                if ($file) {
                    $json = fread($file, filesize($filename));
                    $usersFromJson = json_decode($json, true);
                    foreach ($usersFromJson as $user) {
                        array_push($users, new Usuario($user["_id"], $user["_name"], $user["_surname"], $user["_password"], $user["_email"], $user["_registerDate"], $user["_photoName"]));
                        //$users = array_merge($users, $user);
                    }
                }
                fclose($file);
            } 
        }catch (\Throwable $th) {
            echo "Error while reading the file";
        } 
        finally {
            return $users;
        }
    }

    public function __Equals($obj){
        if (get_class($obj) == "Usuario" &&
            $obj->getId() == $this->getId()) {
            return true;
        }
        return false;
    }

    public static function CheckId($arrayOfUsers, $id)
    {
            foreach ($arrayOfUsers as $aUser) {
            if ($aUser->getId() == $id) {
                return true;
            } else {
                return false;
            }
        }
    }
    
}
?>


