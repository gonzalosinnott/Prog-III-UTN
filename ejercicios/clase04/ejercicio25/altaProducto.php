<?php

/* 

Aplicación No 25 ( AltaProducto)
Archivo: altaProducto.php
método:POST
Recibe los datos del producto(código de barra (6 sifras ),nombre ,tipo, stock, precio )por POST
Crea un ID autoincremental(emulado, puede ser un random de 1 a 10.000). 
Crear un objeto y utilizar sus métodos para poder verificar si es un producto existente, 
si ya existe el producto se le suma el stock , de lo contrario se agrega al documento en un nuevo renglón
Retorna un :
“Ingresado” si es un producto nuevo
“Actualizado” si ya existía y se actualiza el stock.
“no se pudo hacer“si no se pudo hacer
Hacer los métodos necesarios en la clase

Sinnott Segura Gonzalo

*/

require_once 'Producto.php';
    
    $option = $_GET['task'];
    $pBarcode = $_POST['codigo'];
    $pName = $_POST['nombre'];
    $pType = $_POST['tipo'];
    $pStock = $_POST['stock'];
    $pPrice = $_POST['precio'];

    //--- Sets the timezone to use. ---//
    date_default_timezone_set('America/Argentina/Buenos_Aires');

    switch ($option) {
        case 'register':
            $myProduct = new Producto(rand(1,5), $pBarcode, $pName, $pType, $pStock, $pPrice);
            $myArray = Producto::ReadJSON();
            echo '<h1>Producto Creado</h1>';
            var_dump($myProduct);
            $myArray = Producto::UpdateArray($myArray, $myProduct);
            Producto::SaveToJSON($myArray);
            break;
    }
?>