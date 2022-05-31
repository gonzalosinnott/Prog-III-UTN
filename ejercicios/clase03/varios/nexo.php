<?php

require_once "usuario.php";

$opcion = $_GET['Tarea'];
$usuario = $_POST['Nombre'];
$contraseña = $_POST['Contraseña'];

var_dump($opcion);

switch ($opcion)
{
    case 'mostrar':
        Usuario::MostrarUsuario($usuario);
        break;
    case 'alta':
        $usuario = new Usuario($usuario,$contraseña); 
        //Usuario::MostrarUsuario($usuario); 
        array_push($lista, $usuario);
        break;     
}
?>