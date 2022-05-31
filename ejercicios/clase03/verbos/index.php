<?php

switch($_SERVER ['REQUEST_METHOD']){
    case 'GET':
        echo 'PETICION POR GET';
        echo json_encode((['parametro' => $_GET]));
        break;
    case 'POST':
        //$nombre = 'Hola ' . $_POST['nombre'];

        echo json_encode(array('parametro' => $_POST));
        break;
    default:
        echo 'METODO NO SOPORTADO';
        break;
}
?>