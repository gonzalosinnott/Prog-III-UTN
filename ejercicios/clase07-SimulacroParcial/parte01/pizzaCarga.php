<?php

/*

Gonzalo Sinnott Segura

*/

include_once "pizza.php";

class PizzaCarga
{
    private $firstLoad = true;
    private $id = 0;

    public function cargarPizzaPorGet()
    {

        
        if (isset($_GET["Sabor"])  &&
            isset($_GET["Precio"]) &&
            isset($_GET["Tipo"])   &&
            isset($_GET["Cantidad"])) {

            if (!$this->firstLoad) {
                $this->firstLoad;
                $this->id = 1;
            } else {
                $this->id += 1;
            }           

            ////NO SE PORQUE NO GUARDA EL ID
            $pizza = new PizzaModel($this->id, $_GET["Sabor"], $_GET["Precio"], $_GET["Tipo"], $_GET["Cantidad"]);
            return (PizzaModel::cargarPizza("pizza.json", $pizza) ? true : "Error en la carga de la pizza"."<br>");


        } else {
            echo "Error en los parametros ingresados"."<br>";
            return false;
        }
    }
}


?>

