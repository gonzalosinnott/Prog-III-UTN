<?php

/*

Gonzalo Sinnott Segura

*/

include_once 'ventasController.php';

class ConsultaVenta{

    public function consultarVentas(){

        try {
            switch($_GET['tipoConsulta']) { 
                    
                case "a":
                    $fecha = isset($_GET["fecha"]) ? $_GET["fecha"] : null;
                    $data = VentasController::AveriguarCantidadDeHeladosVendidos($fecha);
                    if($data == null)
                        $data = "0";                    
                    echo "Helados Vendidss: ".$data . " \n";
                    return true;
                break;
            
                case "b":
                    if ( isset($_GET["fechaminima"]) && isset($_GET["fechamaxima"])){
                        $data = VentasController::ObtenerVentasEntreFechas($_GET["fechaminima"],$_GET["fechamaxima"]);
                        $js = json_encode($data, JSON_PRETTY_PRINT);
                        echo "Helados Vendidos Entre Fechas ".$_GET["fechaminima"]." y ".$_GET["fechamaxima"] . "\n" .$js . " \n";
                        return true;
                    }else{
                        echo "Debe ingresar una fecha maxima y una minima \n";
                        return false;
                    }                                     
                break;
            
                case "c":
                    if ( isset($_GET["Email"])){
                        $data = VentasController::ObtenerVentasDeUnUsuario($_GET["Email"]);
                        if($data != null)
                        {
                            $js = json_encode($data, JSON_PRETTY_PRINT);
                            echo "Helados del usuario ".$_GET["Email"]." : \n" .  $js . " \n";
                        }else{
                            echo "No hay ventas del usuario ".$_GET["Email"]." \n"; 
                        }
                        return true;
                    }else{
                        echo "Debe ingresar mail del usuario \n";
                        return false;
                    }
                    break;
            
                case "d":
                    if ( isset($_GET["Sabor"])){
                        $data = VentasController::ObtenerVentasDeUnSabor($_GET["Sabor"]);
                        if($data != null)
                        {
                            $js = json_encode($data, JSON_PRETTY_PRINT);
                            echo "Helados de sabor ".$_GET["Sabor"]." : \n" .  $js . " \n";
                        }else{
                            echo "No hay ventas de sabor ".$_GET["Sabor"]." \n"; 
                        }
                        return true;
                    }else{
                        echo "Debe ingresar sabor del helado \n";
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