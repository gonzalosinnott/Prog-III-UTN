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
                echo($pizza->cargarPizza() ? "Producto cargado \n" : "Error en la carga de producto \n");
                break;
            case 'pizzaConsulta':
                /*
                2- (1pt.) PizzaConsultar.php: (por POST)Se ingresa Sabor,Tipo, 
                si coincide con algún registro del archivo Pizza.json,
                retornar “Si Hay”. De lo contrario informar si no existe el tipo o el sabor.
                */   
                include_once "pizzaConsultar.php";
                $pizza = new PizzaConsultar();
                echo($pizza->consultarStock() ? "Si Hay \n" : "No Hay \n");
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
                echo($pizza->ventaPizza() ? "Venta realizada \n" : "Error al realizar la venta \n");
                break;
            case 'ventasConsulta':
                /*
                4- (3 pts.)ConsultasVentas.php: necesito saber :
                a- la cantidad de pizzas vendidas
                b- el listado de ventas entre dos fechas ordenado por sabor.
                c- el listado de ventas de un usuario ingresado
                d- el listado de ventas de un sabor ingresado
                */
                include_once "consultasVentas.php";
                $consulta = new ConsultaVenta();
                echo($consulta->consultarVentas() ? "Consulta realizada con exito \n" : "Error al consultar las ventas \n");
                break;
            case 'devolverPizza':
                /*
                7- (2 pts.)DevolverPizza.php Guardar en el archivo (devoluciones.json y cupones.json):
                Se ingresa el número de pedido y la causa de la devolución. El número de pedido debe existir, 
                seingresa una foto del cliente enojado,esto debe generar un cupón de descuento con el 10% de descuento para la próxima compra..
                */
                include 'devolverPizza.php';
                $devolver = new DevolverPizza();
                echo($devolver->devolverVenta() ? "Pizza devuelta \n" : "Error al devolver la pizza \n");
                break;
            default:
                echo 'METODO NO SOPORTADO';
                break; 
            }        
        break;
    case 'PUT':
        switch ($option)
        {
            case 'modificarVenta':
            /*
            5- (1 pts.) ModificarVenta.php(por PUT), debe recibir el
            número de pedido, el email del usuario, el sabor,tipo y
            cantidad, si existe se modifica , de lo contrario informar.
            */
            include 'modificarVenta.php';
            $modificar = new ModificarVentas();
            echo($modificar->modificarVenta() ? "Venta modificada \n" : "Error al modificar la venta \n");
            break; 
        }
        break;
    case 'DELETE':
        switch ($option)
        {
            case 'borrarVenta':
                /*
                6- (1 pts.) borrarVenta.php(por DELETE), debe recibir un número de pedido,
                se borra la venta y la foto se mueve a la carpeta /BACKUPVENTAS.
                */
                include 'borrarVenta.php';
                $borrar = new BorrarVentas();
                echo($borrar->borrarVenta() ? "Venta borrada \n" : "Error al borrar la venta \n");
                break;            
        }
        break;
    default:
        echo 'METODO NO SOPORTADO';
        break;
}

?>