<?php

/* 

Sinnott Segura Gonzalo

*/

class Ventas{

    public $_clientId;
    public $_code;
    public $_stock;

    public function __construct($clientId, $code, $stock){
        $this->setId($clientId);
        $this->setCode($code);
        $this->setStock($stock);
    }

    public function getId(){
        return $this->_clientId;
    }

    public function getCode(){
        return $this->_code;
    }

    public function getStock(){
        return $this->_stock;
    }   

    public function setId($id){
        if (is_numeric($id)) {
            $this->_clientId = $id;
        }
    }

    public function setCode($code){
        if (!empty($code) && is_string($code) && strlen($code) < 7){
            $this->_code = $code;
        }
    }

    public function setStock($stock){
        if (!empty($stock) && is_numeric($stock)) {
            $this->_stock = $stock;
        }
    }

    public static function UpdateArray($arrayOfSales, $sale){
        
        array_push($arrayOfSales, $sale);

        return $arrayOfSales;
    }

    
    public static function ReadJSON($filename="ventas.json"):array{
        $sales = array();
        try {
            if (file_exists($filename)) {                  
                $file = fopen($filename, "r");
                if ($file) {
                    $json = fread($file, filesize($filename));
                    $salesFromJson = json_decode($json, true);
                    foreach ($salesFromJson as $sale) {
                        array_push($sales, new Ventas($sale["_clientId"], $sale["_code"], $sale["_stock"]));
                    }
                }
                fclose($file);
            } 
        }catch (\Throwable $th) {
            echo "ERROR AL LEER EL ARCHIVO";
        } 
        finally {
            return $sales;
        }
    }

    public static function SaveToJSON($salesArray, $filename="ventas.json"):bool{
        $success = false;
        try {
            $file = fopen($filename, "w");
            if ($file) {
                $json = json_encode($salesArray, JSON_PRETTY_PRINT);
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
