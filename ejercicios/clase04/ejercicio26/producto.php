<?php

/* 

Sinnott Segura Gonzalo

*/

class Producto{

    public $_id;
    public $_code;
    public $_name;
    public $_type;
    public $_stock;
    public $_price;

    public function ConstructorWithArgument2($arg1, $arg2) {
        $this->setCode($arg1);
        $this->setStock($arg2);
    }

    public function ConstructorWithArgument6($arg1, $arg2, $arg3, $arg4, $arg5, $arg6) {
        $this->setId($arg1);
        $this->setCode($arg2);
        $this->setName($arg3);
        $this->setType($arg4);
        $this->setStock($arg5);
        $this->setPrice($arg6);
    }

    public function __construct() {
        $arguments = func_get_args();
        $numberOfArguments = func_num_args();
  
        if (method_exists($this, $function = 
                'ConstructorWithArgument'.$numberOfArguments)) {
            call_user_func_array(
                        array($this, $function), $arguments);
        }
    }   


    public function getId(){
        return $this->_id;
    }

    public function getCode(){
        return $this->_code;
    }

    public function getName(){
        return $this->_name;
    }

    public function getType(){
        return $this->_type;
    }

    public function getStock(){
        return $this->_stock;
    }

    public function getPrice(){
        return $this->_price;
    }

    public function setId($id){
        if (is_numeric($id)) {
            $this->_id = $id;
        }
    }

    public function setCode($code){
        if (!empty($code) && is_string($code) && strlen($code) < 7){
            $this->_code = $code;
        }
    }

    public function setName($name){
        if (!empty($name)) {
            $this->_name = $name;
        }
    }

   public function setType($type){
        if (!empty($type)) {
            $this->_type = $type;
        }
    }

    public function setStock($stock){
        if (!empty($stock) && is_numeric($stock)) {
            $this->_stock = $stock;
        }
    }

    public function setPrice($price){
        if (!empty($price) && is_numeric($price)) {
            $this->_price = $price;
        }
    }

    public static function CheckCode($arrayOfProducts, $id)
    {
        foreach ($arrayOfProducts as $aProduct) {
            if ($aProduct->getCode() == $id) {
                return true;
            } else {
                return false;
            }
        }
    }

    public static function CheckStock($arrayOfProducts, $id, $stock)
    {
            foreach ($arrayOfProducts as $aProduct) {
            if ($aProduct->getCode() == $id){
                if ($aProduct->getStock() > $stock) {
                    return true;
                } else {
                    return false;
                }
            }
        }
             
    }

    public function __Equals($obj){
        if (get_class($obj) == "Producto" &&
            $obj->getCode() == $this->getCode()) {
            return true;
        }
        return false;
    }

    public function ProductInArray($arrayProducts){
        foreach ($arrayProducts as $product) {
            if ($this->__Equals($product)) {
                return true;
            }
        }
        return false;
    }

    

    public static function UpdateArray($arrayOfProducts, $product, $action){
        
        if (!$product->ProductInArray($arrayOfProducts)) {
            if ($action == "add") {
                array_push($arrayOfProducts, $product);
                echo "PRODUCTO AGREGADO";
            }else if ($action == "sub") {
                echo "PRODUCTO INEXISTENTE";
            }
        }else{
            foreach ($arrayOfProducts as $aProduct) {
                if ($aProduct->__Equals($product)) {
                    if($action == "add"){
                        $aProduct->setStock("".$aProduct->getStock() + $product->getStock()."");
                        echo "PRODUCTO ACTUALIZADO";
                    }else if($action == "sub"){
                        if($aProduct->getStock() >= $product->getStock()){
                            $aProduct->setStock("".$aProduct->getStock() - $product->getStock()."");
                        echo "VENTA REALIZADA";
                        }else{
                            echo "STOCK INSUFICIENTE";
                        }
                    }
                    break;
                }
            }
        }
        return $arrayOfProducts;
    }

    public static function ReadJSON($filename = "productos.json"): array
    {
        $products = array();
        try {
            if (file_exists($filename)) {
                $file = fopen($filename, "r");
                if ($file) {
                    $json = fread($file, filesize($filename));
                    $productsFromJson = json_decode($json, true);
                    foreach ($productsFromJson as $product) {
                        array_push($products, new Producto($product["_id"], $product["_code"], $product["_name"], $product["_type"], $product["_stock"], $product["_price"]));
                    }
                }
                fclose($file);
            }
        } catch (\Throwable $th) {
            echo "Error al leer el archivo";
        } finally {
            return $products;
        }
    }

    public static function SaveToJSON($productsArray, $filename="productos.json"){
        $success = false;
        try {
            $file = fopen($filename, "w");
            if ($file) {
                $json = json_encode($productsArray, JSON_PRETTY_PRINT);
                fwrite($file, $json);
                $success = true;
            }
        } catch (\Throwable $th) {
            echo "ERROR AL GUARDAR EL ARCHIVO";
        } finally {
            fclose($file);
            return $success;
        }
    }
}
