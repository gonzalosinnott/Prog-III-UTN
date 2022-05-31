<?php

/*

Gonzalo Sinnott Segura

*/

include_once "dataController.php";

class ConsultarDevoluciones{


    public function consultarDevoluciones(){

        try {

            switch($_GET['tipoConsulta']) { 
                    
                case "a":
                    $data = DataController::listarDevoluciones();
                    $js = json_encode($data, JSON_PRETTY_PRINT);
                    echo "Devoluciones: ".$js . " \n";
                    return true;
                break;
            
                case "b":                    
                    $data = DataController::listarCupones();;
                    $js = json_encode($data, JSON_PRETTY_PRINT);
                    echo "Cupones: ".$js . " \n";
                    return true;                                                       
                    break;
            
                case "c":                    
                    $data = DataController::listarDevolucionesConCupones();;
                    $js = json_encode($data, JSON_PRETTY_PRINT);
                    echo "Devoluciones con Cupones: ".$js . " \n";
                    return true;                                                       
                    break;               
            }  
                 
        } catch (\Throwable $th) {
            echo "ERROR AL REALIZAR LA CONSULTA \n";
            return false;
        }
    }
}
?>

