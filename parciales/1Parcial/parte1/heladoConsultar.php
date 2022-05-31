<?php

/*

Gonzalo Sinnott Segura

*/

include_once "heladoController.php";
include_once "dataController.php";

class HeladoConsultar
{
    public function consultarStock()
    {
        if (isset($_POST["Sabor"]) && isset($_POST["Tipo"])) {

            $helado = new Helado (0, $_POST["Sabor"], 0, $_POST["Tipo"], 0);

            $array = DataController::leerJson("heladeria.json");

            foreach ($array as $producto) {
                if (Helado::checkStock($producto, $helado)) {
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