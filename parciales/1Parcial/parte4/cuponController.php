<?php

/*

GOnzalo Sinnott Segura

*/

include_once "dataController.php";

class Cupon{

    public $cliente;
    public $cuponNumero;
    public $fecha;
    public $usado;
    public $importe;
    public $descuento;

    public function __construct($cliente, $cuponNumero, $fecha, $usado, $importe, $descuento)
    {
        $this->cliente = $cliente;
        $this->cuponNumero = $cuponNumero;
        $this->fecha = $fecha;  
        $this->usado = $usado;
        $this->importe = $importe;
        $this->descuento = $descuento;
    }

    public function getCliente()
    {
        return $this->cliente;
    }

    public function getCuponNumero()
    {
        return $this->cuponNumero;
    }

    public function getFecha()
    {
        return $this->fecha;
    }   

    public function getUsado()
    {
        return $this->usado;
    }   

    public function getImporte()
    {
        return $this->importe;
    }

    public function getDescuento()
    {
        return $this->descuento;
    }

    public function setCliente($cliente)
    {
        $this->cliente = $cliente;
    }

    public function setCuponNumero($cuponNumero)
    {
        $this->cuponNumero = $cuponNumero;
    }

    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    public function setUsado($usado)
    {
        $this->usado = $usado;
    }

    public function setImporte($importe)
    {
        $this->importe = $importe;
    }

    public function setDescuento($descuento)
    {
        $this->descuento = $descuento;
    }

    public static function aplicarCupon($precioHelado, $email)
    {

        try { 

            $output = false;
            $flag = true;
            $cupones = DataController::listarCupones();
            $cliente = strtok($email, '@');
            $precio = $precioHelado;

            
            foreach ($cupones as $cupon) {
                if ($cupon->cliente == $cliente && $cupon->usado == false) {
                    $cupon->SetUsado(true);
                    $cupon->setImporte($precio - ($precio * 0.1));
                    $cupon->setDescuento($precio * 0.1);
                    $output = true;
                    break;
                }
            }

            if ($output) {
                foreach ($cupones as $product) {
                    if (!$flag) {
                        DataController::guardarJson($product, "cupones.json");
                    } else {
                        DataController::guardarJson($product, "cupones.json", 'w');
                        $flag = false;
                    }
                }
            } else {
                echo "No hay cupones para este cliente \n";
            }
        } catch (\Throwable $th) {
            return false;
        }
    }
    
}