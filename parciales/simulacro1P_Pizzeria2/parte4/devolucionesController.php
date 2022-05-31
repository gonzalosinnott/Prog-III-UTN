<?php

/*

Gonzalo Sinnott Segura

*/

class Devoluciones{
    public $numeroDePedido;
    public $cliente;
    public $causa;

    public function __construct($numeroDePedido, $cliente, $causa){
        $this->numeroDePedido = $numeroDePedido;
        $this->cliente = $cliente;
        $this->causa = $causa;
    }

    public function getNumeroDePedido(){
        return $this->numeroDePedido;
    }

    public function getCliente(){
        return $this->cliente;
    }

    public function getCausa(){
        return $this->causa;
    }

    public function setNumeroDePedido($numeroDePedido){
        $this->numeroDePedido = $numeroDePedido;
    }

    public function setCliente($cliente){
        $this->cliente = $cliente;
    }

    public function setCausa($causa){
        $this->causa = $causa;
    }

    
}