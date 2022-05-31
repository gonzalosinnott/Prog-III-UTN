<?php

/*
Gonzalo Sinnott Segura
*/

include_once "pizzaController.php";
include_once "dataController.php";

class PizzaCarga
{

    public function cargarPizza()
    {
        if (
            isset($_POST["Sabor"])    &&
            isset($_POST["Precio"])   &&
            isset($_POST["Tipo"])     &&
            isset($_POST["Cantidad"]) &&
            isset($_FILES["Image"])
        ) {

            if($_POST['Tipo'] == "Piedra" || $_POST['Tipo'] == "Molde"){
                
                $id = DataController::getNextId("pizza.json");

                $pizza = new Pizza($id,$_POST["Sabor"], $_POST["Precio"], $_POST["Tipo"], $_POST["Cantidad"]);

                return (Pizza::cargarPizza("pizza.json", $pizza, $_FILES["Image"]) ? true : "Error en la carga de la pizza \n");
            }
            else{
                echo "Tipo de pizza erroneo \n";
                return false;
            }

        } else {
            echo "Error en los parametros ingresados \n";
            return false;
        }
    }
}


