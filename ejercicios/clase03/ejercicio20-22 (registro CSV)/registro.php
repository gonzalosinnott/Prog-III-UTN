<?php
/*
método:POST
Recibe los datos del usuario(nombre, clave,mail )por POST ,
crear un objeto y utilizar sus métodos para poder hacer el alta,
guardando los datos en usuarios.csv.
retorna si se pudo agregar o no.
Cada usuario se agrega en un renglón diferente al anterior.
Hacer los métodos necesarios en la clase usuario

método:GET
Recibe qué listado va a retornar(ej:usuarios,productos,vehículos,...etc),por ahora solo tenemos
usuarios).
En el caso de usuarios carga los datos del archivo usuarios.csv.
se deben cargar los datos en un array de usuarios.
Retorna los datos que contiene ese array en una lista

método:POST
Recibe los datos del usuario(clave,mail )por POST ,
crear un objeto y utilizar sus métodos para poder verificar si es un usuario registrado,
Retorna un :
“Verificado” si el usuario existe y coincide la clave también.
“Error en los datos” si esta mal la clave.
“Usuario no registrado si no coincide el mail“
Hacer los métodos necesarios en la clase usuario

Hacer los métodos necesarios en la clase usuario

Gonzalo Sinnott Segura

*/

include "usuario.php";

$option = $_GET['task'];
$name = $_POST["nombre"];
$pass = $_POST["clave"];
$mail = $_POST["mail"];
$method = $_SERVER ['REQUEST_METHOD'];

$user = new Usuario($name, $pass, $mail);

switch($method)
{
    case 'POST':
        switch ($option)
        {
            case 'register':
                if($user->GuardarCSV())
                {
                    echo "Usuario agregado";
                }
                else
                {
                    echo "No se pudo agregar el usuario";
                }
                break;
            case 'login':
                switch(Usuario::ValidarUsuario($mail, $pass))
                {
                    case 1:
                        echo "Verificado";
                        break;
                    case 2:
                        echo "Error en los datos";
                        break;
                    case 3:
                        echo "Usuario no registrado";
                        break;
                }
                break;                
            default:
                echo 'METODO NO SOPORTADO';
                break;
        }        
        break;
    case 'GET':
        $user->MostrarUsuarios();
        break;
    default:
        echo 'METODO NO SOPORTADO';
        break;
}


?>