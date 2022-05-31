<?php

/*

Gonzalo Sinnott Segura

*/

include_once 'ventasController.php';

class ConsultaVenta{

    public function consultarVentas(){

        try {
            switch($_POST['tipoConsulta']) { 
                    
                case "a":
                    $fecha = isset($_POST["fecha"]) ? $_POST["fecha"] : null;
                    $data = VentasController::AveriguarCantidadDePizzasVendidas($fecha );
                    echo "Pizzas Vendidas: ".$data . " \n";
                    return true;
                break;
            
                case "b":
                    if ( isset($_POST["fechaminima"]) && isset($_POST["fechamaxima"])){
                        $data = VentasController::ObtenerVentasEntreFechas($_POST["fechaminima"],$_POST["fechamaxima"]);
                        $js = json_encode($data, JSON_PRETTY_PRINT);
                        echo "Pizzas Vendidas Entre Fechas ".$_POST["fechaminima"]." y ".$_POST["fechamaxima"] . "\n" .$js . " \n";
                        return true;
                    }else{
                        echo "Debe ingresar una fecha maxima y una minima \n";
                        return false;
                    }                                     
                break;
            
                case "c":
                    if ( isset($_POST["Email"])){
                        $data = VentasController::ObtenerVentasDeUnUsuario($_POST["Email"]);
                        $js = json_encode($data, JSON_PRETTY_PRINT);
                        echo "Pizzas del usuario ".$_POST["Email"]." : \n" .  $js . " \n";
                        return true;
                    }else{
                        echo "Debe ingresar mail del usuario \n";
                        return false;
                    }
                    break;
            
                case "d":
                    if ( isset($_POST["Sabor"])){
                        $data = VentasController::ObtenerVentasDeUnSabor($_POST["Sabor"]);
                        $js = json_encode($data, JSON_PRETTY_PRINT);
                        echo "Pizzas de sabor ".$_POST["Sabor"]." : \n" .  $js . " \n";
                        return true;
                    }else{
                        echo "Debe ingresar sabor de la pizza \n";
                        return false;
                    }                    
                    break;
            }           
        } catch (\Throwable $th) {
            echo "ERROR AL REALIZAR LA CONSULTA \n";
            return false;
        }

    }
}
?>