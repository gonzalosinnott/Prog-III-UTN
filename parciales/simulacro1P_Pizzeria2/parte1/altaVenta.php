<?php

/*

Gonzalo Sinnott Segura

*/

include_once "ventasController.php";
include_once "pizzaController.php";

class PizzaVenta{

    public function ventaPizza(){

        if (isset($_POST["Email"]) &&
            isset($_POST["Cantidad"]) &&
            isset($_POST["Sabor"]) &&
            isset($_POST["Tipo"])&&
            isset($_FILES["Image"])){

                $pedidoNumero = random_int(1, 10000);
                $pizza = new Pizza(0,$_POST["Sabor"],0,$_POST["Tipo"],$_POST["Cantidad"]);
                return VentasController::venderPizza("Pizza.json", $pizza, $_POST["Email"], $pedidoNumero, $_FILES["Image"]);
            }
        else
        {
            echo "Error en los parametros ingresados \n";
        }
    }
}
?>