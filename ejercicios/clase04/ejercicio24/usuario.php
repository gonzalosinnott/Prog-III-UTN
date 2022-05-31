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

    public function __construct($id, $name, $surname, $password, $email, $registerDate, $photoName){
        $this->setId($id);
        $this->setName($name);
        $this->setSurname($surname);
        $this->setPassword($password);
        $this->setEmail($email);
        $this->setRegisterDate($registerDate);
        $this->setPhotoName($photoName);
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

 
    public static function PrintsInfoOfUsers($users = array()){
        echo "<ul>";
        try {
            if (!empty($users)) {
                    foreach ($users as $user) {
                    echo "<li>"."ID: ".$user->getId()."</li>";
                    echo "<li>"."NOMRE Y APELLIDO: ".$user->getName()." ".$user->getSurname()."</li>";
                    echo "<li>"."PASSWORD: ".$user->getPassword()."</li>";
                    echo "<li>"."EMAIL: ".$user->getEmail()."</li>";
                    echo "<li>"."FECHA DE REGISTRO: ".$user->getRegisterDate()."</li>";
                    echo "<li>"."PERFIL: ".$user->getPhotoName()."</li>";
                }
                echo "</ul>";            }
        } catch (\Throwable $th) {
            echo 'Exception: '.$th->getMessage();
        }finally{
            echo "</ul>";
        }
    }    
}
?>


