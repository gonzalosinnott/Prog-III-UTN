<?php

/* 

Sinnott Segura Gonzalo

*/

class Producto
{

    public $_id;
    public $_code;
    public $_name;
    public $_type;
    public $_stock;
    public $_price;

    public function __construct($id, $code, $name, $type, $stock, $price)
    {
        $this->setId($id);
        $this->setCode($code);
        $this->setName($name);
        $this->setType($type);
        $this->setStock($stock);
        $this->setPrice($price);
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getCode()
    {
        return $this->_code;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getType()
    {
        return $this->_type;
    }

    public function getStock()
    {
        return $this->_stock;
    }

    public function getPrice()
    {
        return $this->_price;
    }

    public function setId($id)
    {
        if (is_int($id)) {
            $this->_id = $id;
        }
    }

    public function setCode($code)
    {
        if (!empty($code) && is_string($code) && strlen($code) == 6) {
            $this->_code = $code;
        }
    }

    public function setName($name)
    {
        if (!empty($name)) {
            $this->_name = $name;
        }
    }

    public function setType($type)
    {
        if (!empty($type)) {
            $this->_type = $type;
        }
    }

    public function setStock($stock)
    {
        if (!empty($stock) && is_numeric($stock)) {
            $this->_stock = $stock;
        }
    }

    public function setPrice($price)
    {
        if (!empty($price) && is_numeric($price)) {
            $this->_price = $price;
        }
    }

    
    public function __Equals($obj):bool{
        if (get_class($obj) == "Producto" &&
            $obj->getCode() == $this->getCode()) {
            return true;
        }
        return false;
    }

   public function ProductInArray($arrayProducts): bool
    {
        foreach ($arrayProducts as $product) {
            if ($this->__Equals($product)) {
                return true;
            }
        }
        return false;
    }

    public static function UpdateArray($arrayOfProducts, $product): array
    {

        if (!$product->ProductInArray($arrayOfProducts)) {
            array_push($arrayOfProducts, $product);
            echo "Ingresado";
        } else {
            foreach ($arrayOfProducts as $aProduct) {
                if ($aProduct->__Equals($product)) {
                    $aProduct->setStock("" . $aProduct->getStock() + $product->getStock() . "");
                    echo "Actualizado";
                    break;
                }
            }
        }
        return $arrayOfProducts;
    }

    /**
     * Reads a file with information of the products.
     *
     * @param string $filename The name of the file to read.
     * @return array The array with the information of the products.
     */
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

    public static function SaveToJSON($productsArray, $filename = "productos.json"): bool
    {
        $success = false;
        try {
            $file = fopen($filename, "w");
            if ($file) {
                $json = json_encode($productsArray, JSON_PRETTY_PRINT);
                fwrite($file, $json);
                $success = true;
            }
        } catch (\Throwable $th) {
            echo "No se pudo hacer";
        } finally {
            fclose($file);
            return $success;
        }
    }
}
?>



