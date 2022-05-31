<?php

/*
Gonzalo Sinnott Segura
*/

include_once "heladoController.php";
include_once "dataController.php";

class HeladoCarga
{

    public function cargarHelado()
    {
        if (
            isset($_POST["Sabor"])    &&
            isset($_POST["Precio"])   &&
            isset($_POST["Tipo"])     &&
            isset($_POST["Cantidad"]) &&
            isset($_FILES["Image"])
        ) {

            if($_POST['Tipo'] == "Agua" || $_POST['Tipo'] == "Crema"){
                
                $id = DataController::getNextId("heladeria.json");

                $helado = new Helado($id,$_POST["Sabor"], $_POST["Precio"], $_POST["Tipo"], $_POST["Cantidad"]);

                return (Helado::cargarHelado("heladeria.json", $helado, $_FILES["Image"]) ? true : "Error en la carga del helado \n");
            }
            else{
                echo "Tipo de helado erroneo \n";
                return false;
            }

        } else {
            echo "Error en los parametros ingresados \n";
            return false;
        }
    }
}


