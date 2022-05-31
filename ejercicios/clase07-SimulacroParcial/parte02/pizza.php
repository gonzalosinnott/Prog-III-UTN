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
    public $image;

    public function __construct($id, $sabor, $precio, $tipo, $cantidad, $image='empty'){
        $this->id = $id;
        $this->sabor = $sabor;
        $this->precio = $precio;
        $this->tipo = $tipo;
        $this->cantidad = $cantidad;
        $this->image = $image;
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

    public function getImage(){
        return $this->image;
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

    public function setImage($image){
        $this->image = $image;
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

    public static function venderPizza($file, $p, $idAutoincremental, $email, $pedidoNumero)
    {

        $venta = self::RealizarVenta($file, $p);
        $output = false;

        if ($venta)
        {
            $date = new DateTime("now");
            $fechaDeVenta = $date->format('Y-m-d');
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta('INSERT INTO venta (id_venta, fecha_de_venta, numero_de_pedido, email, sabor, tipo, cantidad) VALUES  (:idAutoincremental,:fechaDeVenta,:pedidoNumero,:email,:sabor,:tipo,:cantidad)');
            $consulta->bindValue(':idAutoincremental', $idAutoincremental, PDO::PARAM_INT);
            $consulta->bindValue(':fechaDeVenta', $fechaDeVenta, PDO::PARAM_STR);
            $consulta->bindValue(':pedidoNumero', $pedidoNumero, PDO::PARAM_STR);
            $consulta->bindValue(':email', $email, PDO::PARAM_INT);
            $consulta->bindValue(':sabor', $p->sabor, PDO::PARAM_STR);
            $consulta->bindValue(':tipo', $p->tipo, PDO::PARAM_STR);
            $consulta->bindValue(':cantidad', $p->cantidad, PDO::PARAM_STR);
            $consulta->execute();
            self::GuardarFoto($p, $email, $fechaDeVenta, "venta");
            $output = true;
        }
        return $output;
    }

    public static function RealizarVenta($file, $p)
    {
        $array = self::readJson($file);
        $output = false;
        $flag = true;

        foreach ($array as $product){

            if ($product->Equals($p) && $product->cantidad >= $p->cantidad){
                $product->cantidad -= $p->cantidad;
                $output = true; 
                break;
            }
        }
        if ($output){
            foreach ($array as $product){
                if (!$flag){
                    self::savePizzas($product, $file);
                }
                else{ 
                    self::savePizzas($product, $file, 'w');
                    $flag = false;
                }
            }
        }
        else{
            echo "No hay suficientes pizzas en stock"."<br>";
        }
        return $output;
    }

    ////VER PORQUE NO GUARDA LA FOTO
    public static function GuardarFoto($p, $email = "", $fecha = "", $accion)
    {
        switch ($accion)
        {
            case 'venta':                
                $nombreFoto = $p->tipo . "_" . $p->sabor . "_" . strtok($email, '@') . "_" . $fecha . ".jpg";
                $destino = ".\ImagenesDeLaVenta\\";
            break;
        }

        if (!file_exists($destino))
        {
            mkdir($destino, 0777, true);
        }

        $dir = $destino . $nombreFoto;
        move_uploaded_file($p->image["tmp_name"], $dir);
        $p->image = $dir;
    }
}
?>