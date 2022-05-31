<?php

/*

Gonzalo Sinnott Segura

*/

include_once "pizza.php";

class PizzaConsultar
{
    public function consultarStock()
    {
        if (isset($_POST["Sabor"]) && isset($_POST["Tipo"])){
                
            $pizza = new PizzaModel(0,$_POST["Sabor"],0,$_POST["Tipo"],0);
            return ($pizza->checkStock("Pizza.json",$pizza)? true: false);
        }
        else
        {
            echo "Error en los parametros ingresados"."<br>";
        }
    }       
}

?>