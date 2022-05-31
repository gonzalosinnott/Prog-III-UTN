<?php

/*
Gonzalo Sinnott Segura
*/
include_once 'DBController.php';
include_once 'dataController.php';

class Pizza{

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

    public static function cargarPizza($file,$pizza, $imagen){

        $array = DataController::leerJson($file);
        $productoNuevo = true;
        $flag = true;

        foreach ($array as $producto)
        {
            if (self::checkStock($producto, $pizza))
            {                
                $producto->setPrecio($pizza->precio);
                $cantidad = $producto->cantidad += $pizza->cantidad;
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
                        DataController:: guardarFoto($pizza, $imagen, '', '', '', 'carga');
                    }
                    else
                    {
                        DataController::guardarJson($producto, $file, 'w+');
                        DataController:: guardarFoto($pizza, $imagen, '', '', '', 'carga');
                        echo "Stock Actualizado \n";
                        $flag = false;
                    }
                }
            }
            else
            {
                DataController::guardarJson($pizza, $file, 'a+');
                DataController:: guardarFoto($pizza, $imagen, '', '', '', 'carga');
                echo "Pizza Cargada \n";
            }
            
            return true;

        } catch (\Throwable $th) {
            echo "ERROR AL GUARDAR EL PRODUCTO \n";
            return false;
        }
    }

    public static function checkStock($producto, $pizza)
    {        
        return ($producto->getSabor() == $pizza->getSabor() && $producto->getTipo() == $pizza->getTipo()) ? true : false;    
    }

    public static function RealizarVenta($file, $pizza)
    {
        $array = DataController::leerJson($file);
        $output = false;
        $flag = true;

        foreach ($array as $producto){

            if (self::checkStock($producto, $pizza) && $producto->cantidad >= $pizza->cantidad){
                $producto->cantidad -= $pizza->cantidad;
                $pizza->setPrecio($producto->getPrecio());
                $output = true; 
                break;
            }
        }

        if ($output){
            foreach ($array as $product){
                if (!$flag){
                    DataController::guardarJson($product, $file);
                    $pizza->setPrecio($producto->getPrecio());
                }
                else{ 
                    DataController::guardarJson($product, $file, 'w');
                    $pizza->setPrecio($producto->getPrecio());
                    $flag = false;
                }
            }
        }
        else{
            echo "No hay suficientes pizzas en stock \n";
        }

        return $output;
    }  
}
?>