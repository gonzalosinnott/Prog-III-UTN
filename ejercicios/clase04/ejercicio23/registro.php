<?php
/* 

Aplicación No 23 (Registro JSON)
Archivo: registro.php
método:POST
Recibe los datos del usuario(nombre, clave,mail )por POST ,
crea un ID autoincremental(emulado, puede ser un random de 1 a 10.000). 
Crear un dato con la fecha de registro , toma todos los datos y utilizar sus métodos para poder
hacer el alta, guardando los datos en usuarios.json y subir la imagen al servidor en la carpeta
Usuario/Fotos/.
Retorna si se pudo agregar o no.
Cada usuario se agrega en un renglón diferente al anterior.
Hacer los métodos necesarios en la clase usuario.

Sinnott Segura Gonzalo

*/

require_once 'usuario.php';
require_once 'upload.php';

$option = $_GET['task'];
$name = $_POST['nombre'];
$password = $_POST['clave'];
$email = $_POST['mail'];

date_default_timezone_set('America/Argentina/Buenos_Aires');
$newID = 0;
$firstRegister = false;

$UpManager = new Upload($_FILES);
var_dump($option);

switch ($option) {
    case 'register':
        if (!$firstRegister) {
            $firstRegister = true;
            $newID = rand(1, 10001);
        } else {
            $newID +=1;
        }
        $registerDate = new DateTime("now");
        $user = new Usuario($newID, $name, $password, $email, $registerDate->format('d-m-Y H:m:s'));
        $myArray = Usuario::ReadJSON();
        array_push($myArray, $user);
        
        if ($user->SaveToJSON($myArray)) {
            echo "Usuario guardado correctamente<br>";
        } else {
            echo "Error al guardar el usuario";
        }

        if ($UpManager->saveFileIntoDir($_FILES)) {
            echo "Archivo guardado correctamente<br>";
        }
        break;
}

?>