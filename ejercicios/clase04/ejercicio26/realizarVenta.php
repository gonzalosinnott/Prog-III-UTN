<?php

/* 

Aplicación No 26 (RealizarVenta)
Archivo: RealizarVenta.php
método:POST
Recibe los datos del producto(código de barra), del usuario (el id )y la cantidad de ítems por POST .
Verificar que el usuario y el producto exista y tenga stock.
crea un ID autoincremental(emulado, puede ser un random de 1 a 10.000).
carga los datos necesarios para guardar la venta en un nuevo renglón.
Retorna un :
“venta realizada”Se hizo una venta
“no se pudo hacer“si no se pudo hacer
Hacer los métodos necesaris en las clases

Sinnott Segura Gonzalo

*/

require_once 'producto.php';
require_once 'usuario.php';
require_once 'ventas.php';

$option = $_GET['task'];
    $pBarcode = $_POST['codigo'];
    $pStock = $_POST['stock'];
    $cId = $_POST['id'];

    //--- Sets the timezone to use. ---//
    date_default_timezone_set('America/Argentina/Buenos_Aires');

    switch ($option) {
        case 'sell':
            $myProduct = new Producto($pBarcode, $pStock);
            $client = new Usuario ($cId);
            $sale = new Ventas($cId, $pBarcode, $pStock);

            $clientsList = Usuario::ReadJSON();            
            $productsList = Producto::ReadJSON();

            if(!Usuario::CheckId($clientsList, $cId) ){
                echo "EL USUARIO NO EXISTE.";
            }
            
            else if(!Producto::CheckCode($productsList, $pBarcode)){
                echo "EL PRODUCTO NO EXISTE.";
            }
            
            else if(!Producto::CheckStock($productsList, $pBarcode, $pStock)){
                echo "NO HAY STOCK SUFICIENTE.";

            }else{
                
                $myProducts = Producto::UpdateArray($productsList, $myProduct, "sub");            
                Producto::SaveToJSON($myProducts);
                
                $salesList = Ventas::ReadJSON();
                $mySales = Ventas::UpdateArray($salesList, $sale);
                Ventas::SaveToJSON($mySales);
            }

            break;
    }

?>