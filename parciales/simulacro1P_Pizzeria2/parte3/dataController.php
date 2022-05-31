<?php

/*
Gonzalo Sinnott Segura
*/

include_once "pizzaController.php";
include_once "ventasController.php";

class DataController{

    ///get last id from file
    public static function getNextId($file){
        $array = self::leerJson($file);
        $lastId = 0;
        foreach ($array as $key => $value) {
            if($value->id > $lastId){
                $lastId = $value->id;
            }
        }
        return $lastId + 1;        
    }

    public static function leerJson($file)
    {
        $pizzas = array();

        try {
            if (file_exists($file)) {
                $fileSelected = fopen($file, "r");

                if ($fileSelected != false) {
                    while (!feof($fileSelected)) {
                        $_dataObtained = json_decode(fgets($fileSelected), true);
                        if ($_dataObtained != null) {
                            $product = new Pizza($_dataObtained['id'], $_dataObtained['sabor'], $_dataObtained['precio'], $_dataObtained['tipo'], $_dataObtained['cantidad']);
                            array_push($pizzas, $product);
                        }
                    }
                }
                fclose($fileSelected);
            }
        } catch (\Throwable $th) {
            echo "ERROR AL LEER EL JSON \n";
        } finally {
            return $pizzas;
        }
    }

    public static function guardarJson($producto, $path, $mode = 'a')
    {
        $output = false;

        try {

            $file = fopen($path, $mode);            
            if ($file)
            {
                $itWasWritten = fwrite($file, json_encode(get_object_vars($producto)) . "\n");
                if ($itWasWritten != false)
                {
                    $output = true;
                }
            }
        } catch (\Throwable $th) {
            echo "ERROR AL GUARDAR EL JSON \n";
        }
        finally {
            fclose($file);
            return $output;
        }
    }

    public static function guardarFoto($producto, $imagen, $email = "", $fecha = "", $pedidoNumero = "", $accion)
    {
        switch ($accion)
        {
            case 'venta':                
                $nombreFoto = $fecha . "_" . $pedidoNumero . "_" . $producto->tipo . "_" . $producto->sabor . "_" . strtok($email, '@') . "_" .  ".jpg";
                $destino = ".\ImagenesDeLaVenta\\";
                break;
            case 'carga':
                $nombreFoto = $producto->getTipo() . "_" . $producto->getSabor() . ".jpg";
                $destino = ".\ImagenesDePizzas\\"; 
                break;
            case 'devolucion':
                $nombreFoto = $fecha . "_" . $producto . "_" . $pedidoNumero . "_" . $email . ".jpg";
                $destino = ".\ImagenesDevoluciones\\";             
        }             

        if (!file_exists($destino))
        {
            mkdir($destino, 0777, true);
        }

        $dir = $destino . $nombreFoto;
        move_uploaded_file($imagen["tmp_name"], $dir);
    }

    public static function moverFoto($venta)
    {
        $pedidoNumero = array_column($venta, 'numeroDePedido');
        $files = scandir(".\ImagenesDeLaVenta\\"); 
        $originalDir = ".\ImagenesDeLaVenta\\"; 
        $destination = ".\BACKUPVENTAS\\";
        $fileToFind = $pedidoNumero;
        $output = false;

        if (!file_exists($destination))
        {
            mkdir($destination, 0777, true);
        }

        foreach ($files as $file)
        {
            if (strpos($file, $fileToFind[0]))
            {
                echo "Venta encontrada: " . $file . "\n";
                rename($originalDir . $file, $destination . $file);
                $output = true;
                break;
            }
        }
        return $output;
    }
    
    public static function cargarDevolucion($venta, $causa, $imagen){

        try{
            $pedidoNumero = intval(implode(array_column($venta, 'numeroDePedido')));
            $cliente = strtok(implode(array_column($venta, 'email')), '@');

            $arrayDevolucion = array(
                "numeroDePedido" => $pedidoNumero,
                "cliente" => $cliente,
                "causa" => $causa,
            );

            $objectDevoluciones = (object) $arrayDevolucion;
            self::guardarJson($objectDevoluciones, "devoluciones.json");

            $arrayCupones = array(
                "cliente" => $cliente,
                "cuponNumero" => $pedidoNumero,
                "fecha" => date("Y-m-d"),
            );

            $objectCupones = (object) $arrayCupones;
            self::guardarJson($objectCupones, "cupones.json");

            self::guardarFoto($causa, $imagen, $cliente, date("Y-m-d"), $pedidoNumero, "devolucion");

            return true;
        }catch(\Throwable $th){
            return false;
        }
    }
}

?>