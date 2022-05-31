<?php
/* 

Sinnott Segura Gonzalo

*/

class Usuario{
    
    public $_id;
    public $_name;
    public $_password;
    public $_email;
    public $_registerDate;

    public function __construct($id, $name, $password, $email, $registerDate){
        $this->setId($id);
        $this->setName($name);
        $this->setPassword($password);
        $this->setEmail($email);
        $this->setRegisterDate($registerDate);
    }

    public function setName($name){
        if (is_string($name) && !empty($name)) {
            $this->_name = $name;
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

    function setRegisterDate($registerDate){
        if (is_string($registerDate) && !empty($registerDate)) {
            $this->_registerDate = $registerDate;
        }
    }

    public function getName(){
        return $this->_name;
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

    public function SaveToJSON($usersArray, $filename="usuarios.json"){
        $success = false;
        try {
            $file = fopen($filename, "w");
            if ($file) {
                var_dump($usersArray);
                $json = json_encode($usersArray, JSON_PRETTY_PRINT);
                echo $json . '<br>';
                fwrite($file, $json);
                $success = true;
            }
        } catch (\Throwable $th) {
            echo "Error al guardar el archivo";
        } finally {
            fclose($file);
            return $success;
        }
    }

    public static function ReadJSON($filename="usuarios.json"){
        $users = array();
        
        try {
            $file = fopen($filename, "r");
            if ($file) {
                $json = fread($file, filesize($filename));
                $users = json_decode($json, true);
            }
        } catch (\Throwable $th) {
            echo $th;
        } finally {
            fclose($file);
            return $users;
        }
    }
   
}
?>
