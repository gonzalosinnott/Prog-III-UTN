<?php

/*

1° Parcial Programacion III - Sinnott Segura Gonzalo

*/

// 1-A (1 pt.) index.php:Recibe todas las peticiones que realiza el postman,
// y administra a que archivo se debe incluir.

error_reporting(E_ERROR |  E_PARSE); 
$option = $_GET['task'];
$method = $_SERVER ['REQUEST_METHOD'];
date_default_timezone_set('America/Argentina/Buenos_Aires');

switch($method)
{
    case 'POST':
        switch ($option)
        {
            case 'heladoCarga':
                /*                
                B- (1 pt.) HeladeriaAlta.php: (por POST) se ingresa Sabor, Precio, Tipo (“agua” o “crema”),  Stock(unidades).
                Se guardan los datos en en el archivo de texto heladeria.json, tomando un id autoincremental como
                identificador(emulado) .Sí el nombre y tipo ya existen , se actualiza el precio y se suma al stock
                existente.
                completar el alta con imagen del helado, guardando la imagen con el sabor y tipo como
                identificación en la carpeta /ImagenesDeHelados.
                */   
                include_once "heladoCarga.php";
                $helado = new HeladoCarga();
                echo($helado->cargarHelado() ? "Producto cargado" : "Error en la carga de producto");
                break;
            case 'heladoConsulta':
                /*
                2- (1pt.) HeladoConsultar.php: (por POST) Se ingresa Sabor y Tipo, si coincide con
                algún registro del archivo heladeria.json, retornar “existe”. De lo contrario
                informar si no existe el tipo o el nombre.
                */   
                include_once "heladoConsultar.php";
                $helado = new heladoConsultar();
                echo($helado->consultarStock() ? "Si Hay" : "No Hay");
                break;
            case 'heladoVenta':
                /*
                3-
                a- (1 pts.) AltaVenta.php: (por POST) se recibe el email del usuario y el Sabor, Tipo y Stock, si el ítem existe en
                heladeria.json, y hay stock guardar en la base de datos( con la fecha, número de pedido y id autoincremental ) .
                Se debe descontar la cantidad vendida del stock.
                b- (1 pt) Completar el alta de la venta con imagen de la venta (ej:una imagen del usuario), guardando la imagen
                con el sabor+tipo+mail(solo usuario hasta el @) y fecha de la venta en la carpeta /ImagenesDeLaVenta.
                */   
                include_once "altaVenta.php";
                $helado = new HeladoVenta();
                echo($helado->ventaHelado() ? "Venta realizada" : "Error al realizar la venta");
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
            5- (1 pts.) ModificarVenta.php (por PUT)
            Debe recibir el número de pedido, el email del usuario, el nombre, tipo y cantidad, si existe se modifica , de lo
            contrario informar que no existe ese número de pedido.
            */
            include 'modificarVenta.php';
            $modificar = new ModificarVentas();
            echo($modificar->modificarVenta() ? "Venta modificada" : "Error al modificar la venta");
            break; 
            default:
                echo 'METODO NO SOPORTADO';
                break; 
            }
        break;
    case 'GET':
        switch ($option)
        {
            case 'ventasConsulta':
                /*
                4- (1 pts.)ConsultasVentas.php: (por GET)
                Datos a consultar:
                Datos a consultar:
                a- La cantidad de Helados vendidos en un día en particular(se envía por parámetro), si no se pasa fecha, se
                muestran las del día de ayer.
                b- El listado de ventas de un usuario ingresado.
                c- El listado de ventas entre dos fechas ordenado por nombre.
                d- El listado de ventas por sabor ingresado.
                */
                include_once "consultasVentas.php";
                $consulta = new ConsultaVenta();
                echo($consulta->consultarVentas() ? "Consulta realizada con exito" : "Error al consultar las ventas");
                break;
        }
}

?>