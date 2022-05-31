<?php
/*

La clase Punto ha de tener dos atributos privados con acceso de sólo lectura (sólo con
getters), que serán las coordenadas del punto. Su constructor recibirá las coordenadas del
punto.

Gonzalo Sinnott Segura

*/

class Punto
{

    private $_x;
    private $_y;

    public function __construct($x, $y)
    {
        $this->_x = $x;
        $this->_y = $y;
    }

    public function GetX()
    {
        return $this->_x;
    }

    public function GetY()
    {
        return $this->_y;
    }
}
?>