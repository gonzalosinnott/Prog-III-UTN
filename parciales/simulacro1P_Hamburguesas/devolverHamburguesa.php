<?php
/*

//6- (1 pts.) borrarVenta.php(por DELETE), debe recibir un número de pedido,se borra la venta y la foto se
//mueve a la carpeta /BACKUPVENTAS.

Gonzalo Sinnott Segura
*/

include_once "ventasController.php";

class DevolverHamburguesa{

    public function devolverVenta(){

        if(isset($_POST['NumeroDePedido']) && 
           isset($_POST['Causa']) && 
           isset($_FILES['Imagen'])){

            if(VentasController::checkVentaId($_POST['NumeroDePedido'])){
               
                 return VentasController::devolverVenta($_POST['NumeroDePedido'], $_POST['Causa'], $_FILES['Imagen']) ? true : false;               

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