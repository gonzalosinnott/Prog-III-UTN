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
            case 'hamburguesaCarga':
                /*                
                1-B- (1 pt.) HamburguesaCarga.php: (por POST) se ingresa Nombre, Precio, Tipo
                (“simple” o “doble”), Cantidad( de unidades). Se guardan los datos en en el archivo
                de texto Hamburguesas.json, tomando un id autoincremental como identificador 
                emulado) .
                Sí el nombre y tipo ya existen , se actualiza el precio y se suma al stock existente.
                completar el alta con imagen de la hamburguesa, guardando la imagen con el tipo y 
                el nombre como identificación en la carpeta /ImagenesDeHamburguesas.
                */   
                include_once "hamburguesaCarga.php";
                $hamburguesa = new HamburguesaCarga();
                echo($hamburguesa->cargarHamburguesa() ? "Producto cargado" : "Error en la carga de producto");
                break;
            case 'hamburguesaConsulta':
                /*
                2- (1pt.) HamburguesaConsultar.php: (por POST)Se ingresa Nombre, Tipo, 
                si coincide con algún registro del archivo Hamburguesas.json, 
                retornar “Si Hay”. De lo contrario informar si no existe el tipo o el nombre.
                */   
                include_once "hamburguesaConsultar.php";
                $hamburguesa = new HamburguesaConsultar();
                echo($hamburguesa->consultarStock() ? "Si Hay" : "No Hay");
                break;
            case 'hamburguesaVenta':
                /*
                3a- (1 pts.) AltaVenta.php: (por POST)se recibe el email del usuario y el nombre,
                tipo y cantidad ,si el ítem existe en Hamburguesas.json, y hay stock guardar en la
                base de datos( con la fecha, número de pedido y id autoincremental ) y se debe
                descontar la cantidad vendida del stock.
                3b- (1 pt) Completar el alta con imagen de la venta , guardando la imagen con el tipo
                +nombre+mail (solo usuario hasta el @) y fecha de la venta en la carpeta 
                /ImagenesDeLaVenta.
                8- (2 pts.) AltaVenta.php, ...( continuación) Todo lo anterior más...
                a- Debe recibir el cupón de descuento (si existe) y guardar el importe final
                y el descuento aplicado en el archivo.
                b- Debe marcarse el cupón como ya usado
                */   
                include_once "altaVenta.php";
                $hamburguesa = new HamburguesaVenta();
                echo($hamburguesa->ventaHamburguesa() ? "Venta realizada" : "Error al realizar la venta");
                break; 
            case 'ventasConsulta':
                /*
                4- (1 pts.)ConsultasVentas.php: necesito saber :
                a- La cantidad de Hamburguesas vendidas en un día en particular, si no se pasa
                fecha, se muestran las del día de ayer.
                b- El listado de ventas entre dos fechas ordenado por nombre.
                c- El listado de ventas de un usuario ingresado.
                d- El listado de ventas de un tipo ingresado.
                */
                include_once "consultasVentas.php";
                $consulta = new ConsultaVenta();
                echo($consulta->consultarVentas() ? "Consulta realizada con exito" : "Error al consultar las ventas");
                break;
            case 'devolverHamburguesa':
                /*
                7- (2 pts.) DevolverHamburguesa.php Guardar en el archivo (devoluciones.json y cupones.json):
                a- Se ingresa el número de pedido y la causa de la devolución. El número de pedido debe
                existir, se ingresa una foto del cliente enojado,esto debe generar un cupón de descuento
                con el 10% de descuento para la próxima compra.
                */
                include 'devolverHamburguesa.php';
                $devolver = new DevolverHamburguesa();
                echo($devolver->devolverVenta() ? "Hamburguesa devuelta \n" : "Error al devolver la hamburguesa \n");
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
                5- (1 pts.) ModificarVenta.php (por PUT), debe recibir el número de pedido, el email del
                usuario, el nombre, tipo y cantidad, si existe se modifica , de lo contrario informar.
                */
                include 'modificarVenta.php';
                $modificar = new ModificarVentas();
                echo($modificar->modificarVenta() ? "Venta modificada" : "Error al modificar la venta");
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
    case 'GET':
        switch ($option)
        {
            case 'consultarDevoluciones':
                /*
                9- (2 pts.)ConsultasDevoluciones.php:-
                a-Listar las devoluciones con cupones.
                b-Listar solo los cupones y su estado
                c- listar devoluciones y sus cupones y si fueron usados
                */
                include 'consultarDevoluciones.php';
                $consultar = new ConsultarDevoluciones();
                echo($consultar->consultarDevoluciones() ? "Consulta realizada con exito \n" : "Error al consultar las devoluciones \n");
                break;            
        }
        break;
    default:
        echo 'METODO NO SOPORTADO';
        break;
}

?>