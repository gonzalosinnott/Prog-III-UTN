<?php
/*
Aplicación No 27 (Registro BD)
Archivo: registro.php
método:POST
Recibe los datos del usuario( nombre,apellido, clave,mail,localidad )por POST ,
crear un objeto con la fecha de registro y utilizar sus métodos para poder hacer el alta,
guardando los datos la base de datos
retorna si se pudo agregar o no.
Aplicación No 28 ( Listado BD)
Archivo: listado.php
método:GET
Recibe qué listado va a retornar(ej:usuarios,productos,ventas)
cada objeto o clase tendrán los métodos para responder a la petición
devolviendo un listado <ul> o tabla de html <table>
Aplicación No 29( Login con bd)
Archivo: Login.php
método:POST
Recibe los datos del usuario(clave,mail )por POST ,
crear un objeto y utilizar sus métodos para poder verificar si es un usuario registrado en la
base de datos,
Retorna un :
“Verificado” si el usuario existe y coincide la clave también.
“Error en los datos” si esta mal la clave.
“Usuario no registrado si no coincide el mail“
Hacer los métodos necesarios en la clase usuario.
Gonzalo Sinnott Segura
*/

include "userModel.php";

error_reporting(E_ERROR |  E_PARSE); 

$option = $_GET['task'];
$name = $_POST["nombre"];
$surname = $_POST["apellido"];
$pass = $_POST["clave"];
$mail = $_POST["mail"];
$location = $_POST["localidad"];
$method = $_SERVER ['REQUEST_METHOD'];

date_default_timezone_set('America/Argentina/Buenos_Aires');
$user = new UserModel($name, $surname, $pass, $mail, $location);

switch($method)
{
    case 'POST':
        switch ($option)
        {
            case 'register':
                echo ($user->Register()) ? "Usuario registrado" : "No se pudo registrar";                              
                break;
            case 'login':
                echo ($user->Login()) ? "Verificado" : "Error en los datos - Usuario no registrado";
                    break;;                     
            default:
                echo 'METODO NO SOPORTADO';
                break;
        }        
        break;
    case 'GET':
        switch ($option)
        {
            case 'listUsers':
                $users = $user->List();
                foreach ($users as $user)
                {
                    echo  "<ul>"
                        . "<li>" . "--- User ---" . "<br>" . "</li>"
                        . "<li>" . "Name: " . $user['name'] . "<br>" . "</li>"
                        . "<li>" . "Surname: " . $user['surname'] . "<br>" . "</li>"
                        . "<li>" . "Password: " . $user['pass'] . "<br>" . "</li>"
                        . "<li>" . "Mail: " . $user['mail'] . "<br>" . "</li>"
                        . "<li>" . "Location: " . $user['location'] . "<br>" . "</li>"
                        . "<li>" . "Register Date: " . $user['registerDate'] . "<br>" . "</li>"
                        . "</ul>";
                }
                break;
            default:
                echo 'METODO NO SOPORTADO';
                break;
        }
        break;
    default:
        echo 'METODO NO SOPORTADO';
        break;
}

?