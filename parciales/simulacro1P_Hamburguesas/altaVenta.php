<?php

/*

Gonzalo Sinnott Segura

*/

include_once "ventasController.php";
include_once "hamburguesaController.php";

class HamburguesaVenta{

    public function ventaHamburguesa(){

        if (isset($_POST["Email"]) &&
            isset($_POST["Cantidad"]) &&
            isset($_POST["Nombre"]) &&
            isset($_POST["Tipo"])&&
            isset($_FILES["Image"])){

                $pedidoNumero = random_int(1, 10000);
                $hamburguesa = new Hamburguesa(0,$_POST["Nombre"],0,$_POST["Tipo"],$_POST["Cantidad"]);
                return VentasController::venderHamburguesa("hamburguesas.json", $hamburguesa, $_POST["Email"], $pedidoNumero, $_FILES["Image"]);
            }
        else
        {
            echo "Error en los parametros ingresados \n";
        }
    }
}
?>