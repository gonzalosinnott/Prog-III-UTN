<?php

/*

Gonzalo Sinnott Segura

*/

include_once "pizzaController.php";
include_once "dataController.php";

class PizzaConsultar
{
    public function consultarStock()
    {
        if (isset($_POST["Sabor"]) && isset($_POST["Tipo"])) {

            $pizza = new Pizza(0, $_POST["Sabor"], 0, $_POST["Tipo"], 0);

            $array = DataController::leerJson("Pizza.json");

            foreach ($array as $producto) {
                if (Pizza::checkStock($producto, $pizza)) {
                    return true;
                    break;
                }
            }

            return false;
        
        } else {
            echo "Error en los parametros ingresados" . "<br>";
        }        
    }
}

?>