<?php
/*

Gonzalo Sinnott Segura

*/

include_once "ventasController.php";

class ModificarVentas {

    public function modificarVenta() { 

        $body = json_decode(file_get_contents("php://input"), true);

        if(isset($body['Nombre']) &&
            isset($body['Email']) && 
            isset($body['Tipo']) && 
            isset($body['Cantidad']) && 
            isset($body['NumeroDePedido'])){

            if(VentasController::checkVentaId($body['NumeroDePedido'])){
                return VentasController::modificarVenta($body['NumeroDePedido'],$body['Email'],$body['Nombre'],$body['Tipo'],$body['Cantidad']);
            }else{
                echo "No existe la venta \n";
                return false;
            }
                        
        } else {
            echo "Error en los parametros ingresados";
        }
    }
}

?>