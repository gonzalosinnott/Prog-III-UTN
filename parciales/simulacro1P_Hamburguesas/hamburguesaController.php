<?php

/*
Gonzalo Sinnott Segura
*/
include_once 'DBController.php';
include_once 'dataController.php';

class Hamburguesa{

    public $id;
    public $nombre;
    public $precio;
    public $tipo;
    public $cantidad;

    public function __construct($id,$nombre,$precio,$tipo,$cantidad)
    {
        $this->id = intval($id);
        $this->nombre = $nombre;
        $this->precio = intval($precio);
        $this->tipo = $tipo;
        $this->cantidad = intval($cantidad);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function getCantidad()
    {
        return $this->cantidad;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    }

    public static function cargarHamburguesa($file,$hamburguesa, $imagen){

        $array = DataController::leerJson($file);
        $productoNuevo = true;
        $flag = true;

        foreach ($array as $producto)
        {
            if (self::checkStock($producto, $hamburguesa))
            {                
                $producto->setPrecio($hamburguesa->precio);
                $cantidad = $producto->cantidad += $hamburguesa->cantidad;
                $producto->setCantidad($cantidad);
                $productoNuevo = false;                 
                break;
            }
        }

        try {
            if (!$productoNuevo)
            { 
                foreach ($array as $producto)
                {
                    if (!$flag)
                    {
                        DataController::guardarJson($producto, $file);
                        DataController:: guardarFoto($hamburguesa, $imagen, '', '', '', 'carga');
                    }
                    else
                    {
                        DataController::guardarJson($producto, $file, 'w+');
                        DataController:: guardarFoto($hamburguesa, $imagen, '', '', '', 'carga');
                        echo "Stock Actualizado \n";
                        $flag = false;
                    }
                }
            }
            else
            {
                DataController::guardarJson($hamburguesa, $file, 'a+');
                DataController:: guardarFoto($hamburguesa, $imagen, '', '', '', 'carga');
                echo "Hamburguesa Cargada \n";
            }
            
            return true;

        } catch (\Throwable $th) {
            echo "ERROR AL GUARDAR EL PRODUCTO \n";
            return false;
        }
    }

    public static function checkStock($producto, $hamburguesa)
    {        
        return ($producto->getNombre() == $hamburguesa->getNombre() && $producto->getTipo() == $hamburguesa->getTipo()) ? true : false;    
    }

    public static function RealizarVenta($file, $hamburguesa)
    {
        $array = DataController::leerJson($file);
        $output = false;
        $flag = true;

        foreach ($array as $producto){

            if (self::checkStock($producto, $hamburguesa) && $producto->cantidad >= $hamburguesa->cantidad){
                $producto->cantidad -= $hamburguesa->cantidad;
                $output = true; 
                break;
            }
        }

        if ($output){
            foreach ($array as $product){
                if (!$flag){
                    DataController::guardarJson($product, $file);
                }
                else{ 
                    DataController::guardarJson($product, $file, 'w');
                    $flag = false;
                }
            }
        }
        else{
            echo "No hay suficientes hamburguesas en stock \n";
        }
        return $output;
    }  
}
?>