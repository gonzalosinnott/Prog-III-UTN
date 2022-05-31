<?php
/*

//6- (1 pts.) borrarVenta.php(por DELETE), debe recibir un número de pedido,se borra la venta y la foto se
//mueve a la carpeta /BACKUPVENTAS.

Gonzalo Sinnott Segura
*/

include_once "ventasController.php";
include_once "dataController.php";

class BorrarVentas{

    public function borrarVenta(){

        $body = json_decode(file_get_contents("php://input"), true);

        if(isset($body['NumeroDePedido'])){

            if(VentasController::checkVentaId($body['NumeroDePedido'])){

               
                $venta = VentasController::borrarVenta($body['NumeroDePedido']);

                if($venta){
                    return DataController::moverFoto($venta) ? true : false;
                }else{
                    echo "Error al borrar la venta \n";
                    return false;
                }

            }else{
                echo "No existe la venta \n";
                return false;
            }

        } else {
            echo "Error en los parametros ingresados \n";
        }
    }
}



?>