<?php
/*

Gonzalo Sinnott Segura

*/
class Usuario{

    private $_nombre;
    private $_clave;
    private $_mail;


    public function __construct($nombre, $clave, $mail)
    {
        $this->_nombre = $nombre;
        $this->_clave = $clave;
        $this->_mail = $mail;
    }

    public function GuardarCSV()
    {
        $archivo = fopen("usuarios.csv", "a+");
        $success = false;
        if($archivo)
        {
            fwrite($archivo, $this->_nombre . "," . $this->_clave . "," . $this->_mail . PHP_EOL);
            $success = true;
        }

        return $success;        
    }

    public static function LeerCSV()
    {
        $usuarios = array();
        $archivo = fopen("usuarios.csv", "r");
        if($archivo)
        {
            while(!feof($archivo))
            {
                $linea = fgets($archivo);
                if(!empty($linea))
                {
                    $linea = str_replace(PHP_EOL, '', $linea);
                    $data = explode(",", $linea);
                    $usuario = new Usuario($data[0], $data[1], $data[2]);
                    array_push($usuarios, $usuario);
                }
            }
            
        }
        fclose($archivo);
        return $usuarios;
    }

    public static function MostrarUsuarios()
    {
        $success = false;
        $usuarios = Usuario::LeerCSV();

        echo "<ul>";
        foreach ($usuarios as $user) {
            echo "<li>".$user->_nombre."</li>";
            echo "<li>".$user->_clave."</li>";
            echo "<li>".$user->_mail."</li>";
            $success = true;
        }
        echo "</ul>";

        return $success;
    }

    public static function ValidarUsuario($mail, $pass)
    {
        $users = array();
       
        $users = Usuario::LeerCSV();
        if ($users) {
            foreach ($users as $user) 
            {
                if ($user->_mail == $mail && $user->_clave == $pass) {
                    return 1;
                } 
                else if($user->_mail == $mail && $user->_clave != $pass)
                {
                    return 2;
                }
                else
                {
                    return 3;
                }
                      
            }            
        }        
    }
}
?>