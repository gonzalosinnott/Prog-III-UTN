<?php

/*

Gonzalo Sinnott Segura

*/

include_once "ventasController.php";
include_once "heladoController.php";

class HeladoVenta{

    public function ventaHelado(){

        if (isset($_POST["Email"]) &&
            isset($_POST["Cantidad"]) &&
            isset($_POST["Sabor"]) &&
            isset($_POST["Tipo"])&&
            isset($_FILES["Image"])){

                $pedidoNumero = random_int(1, 10000);
                $helado = new Helado(0,$_POST["Sabor"],0,$_POST["Tipo"],$_POST["Cantidad"]);
                return VentasController::venderHelado("heladeria.json", $helado, $_POST["Email"], $pedidoNumero, $_FILES["Image"]);
            }
        else
        {
            echo "Error en los parametros ingresados \n";
        }
    }
}
?>