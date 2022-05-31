<?php

/*
Gonzalo Sinnott Segura
*/

include_once "hamburguesaController.php";
include_once "dataController.php";

class HamburguesaCarga
{

    public function cargarHamburguesa()
    {
        if (
            isset($_POST["Nombre"])    &&
            isset($_POST["Precio"])   &&
            isset($_POST["Tipo"])     &&
            isset($_POST["Cantidad"]) &&
            isset($_FILES["Image"])
        ) {

            if($_POST['Tipo'] == "Simple" || $_POST['Tipo'] == "Doble"){
                
                $id = DataController::getNextId("hamburguesas.json");

                $hamburguesa = new Hamburguesa($id,$_POST["Nombre"], $_POST["Precio"], $_POST["Tipo"], $_POST["Cantidad"]);

                return (Hamburguesa::cargarHamburguesa("hamburguesas.json", $hamburguesa, $_FILES["Image"]) ? true : "Error en la carga de la hamburguesa \n");
            }
            else{
                echo "Tipo de hamburguesa erronea \n";
                return false;
            }

        } else {
            echo "Error en los parametros ingresados \n";
            return false;
        }
    }
}


