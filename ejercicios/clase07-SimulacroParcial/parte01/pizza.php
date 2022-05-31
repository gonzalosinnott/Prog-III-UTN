<?php

/*

Gonzalo Sinnott Segura

*/

include_once "AccesoDatos.php";

class PizzaModel{

    public $id;
    public $sabor;
    public $precio;
    public $tipo;
    public $cantidad;

    public function __construct($id, $sabor, $precio, $tipo, $cantidad){
        $this->id = $id;
        $this->sabor = $sabor;
        $this->precio = $precio;
        $this->tipo = $tipo;
        $this->cantidad = $cantidad;        
    }

    public function getId(){
        return $this->id;
    }

    public function getSabor(){
        return $this->sabor;
    }

    public function getPrecio(){
        return $this->precio;
    }

    public function getTipo(){
        return $this->tipo;
    }

    public function getCantidad(){
        return $this->cantidad;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function setSabor($sabor){
        $this->sabor = $sabor;
    }

    public function setPrecio($precio){
        $this->precio = $precio;
    }

    public function setTipo($tipo){
        $this->tipo = $tipo;
    }

    public function setCantidad($cantidad){
        $this->cantidad = $cantidad;
    }

    //Valida el ingreso de los datos y los guarda en el JSON
    public static function cargarPizza($file, $p)
    {
        $array = self::readJson($file);
        $productoNuevo = true;
        $flag = true;

        foreach ($array as $product)
        {
            if ($product->checkStock($file))
            {
                
                $product->setPrecio($p->precio);
                $cantidad = $product->cantidad += $p->cantidad;
                $product->setCantidad($cantidad);
                $productoNuevo = false;                 
                break;
            }
        }

        if (!$productoNuevo)
        { 
            foreach ($array as $product)
            {
                if (!$flag)
                {
                    self::savePizzas($product, $file);
                }
                else
                {
                    self::savePizzas($product, $file, 'w');
                    echo "Stock Actualizado <br>";
                    $flag = false;
                }
            }
        }
        else
        {
            self::savePizzas($p, $file, 'a');
            echo "Pizza Cargada"."<br>";
        }
    }

    public function checkStock($file)
    {
        $array = self::readJson($file);       

        foreach ($array as $product)
        {

            if ($this->Equals($product))
            {
                return true;
            }
        }
        return false;
    }

    public function Equals($obj){
        if (get_class($obj) == "PizzaModel" &&
            $obj->getSabor() == $this->getSabor() &&
            $obj->getTipo() == $this->getTipo()) {
            return true;
        }
        return false;
    }

    //Lee el JSON y lo guarda en un array
    public static function readJson($file)
    {
        $pizzas = array();

        try {
            if (file_exists($file)) {
                $fileSelected = fopen($file, "r");

                if ($fileSelected != false) {
                    while (!feof($fileSelected)) {
                        $_dataObtained = json_decode(fgets($fileSelected), true);
                        if ($_dataObtained != null) {
                            //creo el producto con los data obtenidos
                            $product = new PizzaModel($_dataObtained['id'], $_dataObtained['sabor'], $_dataObtained['precio'], $_dataObtained['tipo'], $_dataObtained['cantidad']);
                            array_push($pizzas, $product);
                        }
                    }
                }
                fclose($fileSelected);
            }
        } catch (\Throwable $th) {
            echo "ERROR AL LEER EL JSON"."<br>";
        } finally {
            return $pizzas;
        }
    }

    // Guarda el producto en el JSON
    public static function savePizzas($product, $path, $mode = 'a')
    {
        $output = false;

        try {

            $file = fopen($path, $mode);            
            if ($file)
            {
                $_itWasWritten = fwrite($file, json_encode(get_object_vars($product)) . "\n");
                if ($_itWasWritten != false)
                {
                    $output = true;
                }
            }
        } catch (\Throwable $th) {
            echo "ERROR AL GUARDAR EL JSON"."<br>";
        }
        finally {
            fclose($file);
            return $output;
        }
    }    
}
?>