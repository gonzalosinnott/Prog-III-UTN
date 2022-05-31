<?php

/*

Gonzalo Sinnott Segura

*/

include_once "pizza.php";

class PizzaVenta{

    private $firstLoad = true;
    private $id = 0;


    public function ventaPizza(){

        if (isset($_POST["Email"]) &&
            isset($_POST["Cantidad"]) &&
            isset($_POST["Sabor"]) &&
            isset($_POST["Tipo"])&&
            isset($_FILES["Image"])){

                if (!$this->firstLoad) {
                    $this->firstLoad;
                    $this->id = 1;
                } else {
                    $this->id += 1;
                }               

                $pedidoNumero = random_int(1, 10000);
                $pizza = new PizzaModel(0,$_POST["Sabor"],0,$_POST["Tipo"],$_POST["Cantidad"],$_FILES["Image"]);
                return PizzaModel::venderPizza("Pizza.json", $pizza, $this->id, $_POST["Email"], $pedidoNumero);
            }
        else
        {
            echo "Error en los parametros ingresados";
        }
    }
}
?>