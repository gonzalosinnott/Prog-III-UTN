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
                8- (2 pts.) AltaVenta.php, ...( continuación de 1ra parte, punto 3) Todo lo anterior más...
                a- Debe recibir el cupón de descuento (si existe) y guardar el importe final y el descuento aplicado en el archivo.
                b- Debe marcarse el cupón como ya usado.
                */   
                include_once "altaVenta.php";
                $helado = new HeladoVenta();
                echo($helado->ventaHelado() ? "Venta realizada" : "Error al realizar la venta");
                break; 
            case 'devolverHelado':
                /*
                6- (2 pts.) DevolverHelado.php (por POST),
                Guardar en el archivo (devoluciones.json y cupones.json):
                a- Se ingresa el número de pedido y la causa de la devolución. El número de pedido debe existir, se ingresa una
                foto del cliente enojado,esto debe generar un cupón de descuento(id, devolucion_id, porcentajeDescuento,
                estado[usado/no usadol]) con el 10% de descuento para la próxima compra.
                */
                include 'devolverHelado.php';
                $devolver = new DevolverHelado();
                echo($devolver->devolverVenta() ? "Helado devuelto \n" : "Error al devolver el helado \n");
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