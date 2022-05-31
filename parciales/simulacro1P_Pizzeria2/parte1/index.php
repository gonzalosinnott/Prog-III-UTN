<?php

/*

Simulacro 1° Parcial Programacion III - Sinnott Segura Gonzalo

*/

// 1-A (1 pt.) index.php:Recibe todas las peticiones que realiza el postman, y administra a que archivo se debe incluir.

error_reporting(E_ERROR |  E_PARSE); 
$option = $_GET['task'];
$method = $_SERVER ['REQUEST_METHOD'];
date_default_timezone_set('America/Argentina/Buenos_Aires');

switch($method)
{
    case 'POST':
        switch ($option)
        {
            case 'pizzaCarga':
                /*                
                1-B (1 pt.) PizzaCarga.php: (por POST)se ingresa Sabor, precio, Tipo (“molde” o “piedra”), cantidad( de unidades).
                Se guardan los datos en en el archivo de texto Pizza.json, tomando un id autoincremental como identificador(emulado).
                Sí el sabor y tipo ya existen , se actualiza el precio y se suma al stock existente.
                Completar el alta con imagen de la pizza, guardando la imagen con el tipo y el sabor como nombre en la carpeta /ImagenesDePizzas.
                */   
                include_once "pizzaCarga.php";
                $pizza = new PizzaCarga();
                echo($pizza->cargarPizza() ? "Producto cargado" : "Error en la carga de producto");
                break;
            case 'pizzaConsulta':
                /*
                2- (1pt.) PizzaConsultar.php: (por POST)Se ingresa Sabor,Tipo, 
                si coincide con algún registro del archivo Pizza.json,
                retornar “Si Hay”. De lo contrario informar si no existe el tipo o el sabor.
                */   
                include_once "pizzaConsultar.php";
                $pizza = new PizzaConsultar();
                echo($pizza->consultarStock() ? "Si Hay" : "No Hay");
                break;
            case 'pizzaVenta':
                /*
                3a- (1 pts.) AltaVenta.php: (por POST)se recibe el email del usuario y el sabor,tipo y cantidad 
                , si el ítem existe en  Pizza.json, y hay stock guardar en la base de datos
                (con la fecha, número de pedido y id autoincremental ) y se debe descontar la cantidad vendida del stock.
                3b- (1 pt) completar el alta con imagen de la venta , guardando la imagen con el tipo+sabor+mail
                (solo usuario hasta el @) y fecha de la venta en la carpeta /ImagenesDeLaVenta.
                */   
                include_once "altaVenta.php";
                $pizza = new PizzaVenta();
                echo($pizza->ventaPizza() ? "Venta realizada" : "Error al realizar la venta");
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

?>