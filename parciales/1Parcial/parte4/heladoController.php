<?php

/*
Gonzalo Sinnott Segura
*/
include_once 'DBController.php';
include_once 'dataController.php';

class Helado{

    public $id;
    public $sabor;
    public $precio;
    public $tipo;
    public $cantidad;

    public function __construct($id,$sabor,$precio,$tipo,$cantidad)
    {
        $this->id = intval($id);
        $this->sabor = $sabor;
        $this->precio = intval($precio);
        $this->tipo = $tipo;
        $this->cantidad = intval($cantidad);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSabor()
    {
        return $this->sabor;
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

    public function setSabor($sabor)
    {
        $this->sabor = $sabor;
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

    public static function cargarHelado($file,$helado, $imagen){

        $array = DataController::leerJson($file);
        $productoNuevo = true;
        $flag = true;

        foreach ($array as $producto)
        {
            if (self::checkStock($producto, $helado))
            {                
                $producto->setPrecio($helado->precio);
                $cantidad = $producto->cantidad += $helado->cantidad;
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
                        DataController:: guardarFoto($helado, $imagen, '', '', '', 'carga');
                    }
                    else
                    {
                        DataController::guardarJson($producto, $file, 'w+');
                        DataController:: guardarFoto($helado, $imagen, '', '', '', 'carga');
                        echo "Stock Actualizado \n";
                        $flag = false;
                    }
                }
            }
            else
            {
                DataController::guardarJson($helado, $file, 'a+');
                DataController:: guardarFoto($helado, $imagen, '', '', '', 'carga');
                echo "Helado Cargado \n";
            }
            
            return true;

        } catch (\Throwable $th) {
            echo "ERROR AL GUARDAR EL PRODUCTO \n";
            return false;
        }
    }

    public static function checkStock($producto, $helado)
    {        
        return ($producto->getSabor() == $helado->getSabor() && $producto->getTipo() == $helado->getTipo()) ? true : false;    
    }
    public static function RealizarVenta($file, $helado)
    {
        $array = DataController::leerJson($file);
        $output = false;
        $flag = true;

        foreach ($array as $producto){

            if (self::checkStock($producto, $helado) && $producto->cantidad >= $helado->cantidad){
                $producto->cantidad -= $helado->cantidad;
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
            echo "No hay suficientes helados en stock \n";
        }
        return $output;
    }  
}
?>