<?php

/*
Gonzalo Sinnott Segura
*/

include_once "heladoController.php";

class DataController{

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
        $helados = array();

        try {
            if (file_exists($file)) {
                $fileSelected = fopen($file, "r");

                if ($fileSelected != false) {
                    while (!feof($fileSelected)) {
                        $_dataObtained = json_decode(fgets($fileSelected), true);
                        if ($_dataObtained != null) {
                            $product = new Helado($_dataObtained['id'], $_dataObtained['sabor'], $_dataObtained['precio'], $_dataObtained['tipo'], $_dataObtained['cantidad']);
                            array_push($helados, $product);
                        }
                    }
                }
                fclose($fileSelected);
            }
        } catch (\Throwable $th) {
            echo "ERROR AL LEER EL JSON \n";
        } finally {
            return $helados;
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

    public static function guardarFoto($producto, $imagen, $email = "", $fecha = "", $pedidoNumero = "" , $accion)
    {
        switch ($accion)
        {
            case 'venta':                
                $nombreFoto = $fecha . "_" . $pedidoNumero . "_" . $producto->tipo . "_" . $producto->sabor . "_" . strtok($email, '@') . "_" .  ".jpg";
                $destino = ".\ImagenesDeLaVenta\\";
                break;
            case 'carga':
                $nombreFoto = $producto->getTipo() . "_" . $producto->getSabor() . ".jpg";
                $destino = ".\ImagenesDeHelados\\"; 
                break;            
        }             
          

        if (!file_exists($destino))
        {
            mkdir($destino, 0777, true);
        }

        $dir = $destino . $nombreFoto;
        move_uploaded_file($imagen["tmp_name"], $dir);
    }
}

?>