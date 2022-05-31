<?php

/*

Gonzalo Sinnott Segura

*/

include_once "hamburguesaController.php";
include_once "dataController.php";

class HamburguesaConsultar
{
    public function consultarStock()
    {
        if (isset($_POST["Nombre"]) && isset($_POST["Tipo"])) {

            $hamburguesa = new Hamburguesa(0, $_POST["Nombre"], 0, $_POST["Tipo"], 0);

            $array = DataController::leerJson("hamburguesas.json");

            foreach ($array as $producto) {
                if (Hamburguesa::checkStock($producto, $hamburguesa)) {
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