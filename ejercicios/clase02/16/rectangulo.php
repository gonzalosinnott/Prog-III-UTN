<?php
/*

La clase Rectangulo tiene los atributos privados de tipo Punto _vertice1, _vertice2, _vertice3
y _vertice4 (que corresponden a los cuatro vértices del rectángulo).
La base de todos los rectángulos de esta clase será siempre horizontal. Por lo tanto, debe tener
un constructor para construir el rectángulo por medio de los vértices 1 y 3.
Los atributos ladoUno, ladoDos, área y perímetro se deberán inicializar una vez construido el
rectángulo.

Gonzalo Sinnott Segura

*/

include "punto.php";

class Rectangulo
{

    private Punto $_vertice1;
    private Punto $_vertice2;
    private Punto $_vertice3;
    private Punto $_vertice4;
    private $area;
    private $perimetro;
    private $ladoUno;
    private $ladoDos;

    public function __construct(Punto $vertice1, Punto $vertice3)
    {
        $this->_vertice1 = $vertice1;
        $this->_vertice3 = $vertice3;
    }

    public function Dibujar()
    {
        ///Solucion con HTML, no se si es la ideal pero funciona

        echo "<div style='position: absolute; top: " . $this->_vertice1->GetY() . "px; left: " . $this->_vertice1->GetX() . "px; width: " . ($this->_vertice3->GetX() - $this->_vertice1->GetX()) . "px; height: " . ($this->_vertice3->GetY() - $this->_vertice1->GetY()) . "px; background-color: #ff0000;'></div>";
        
        echo "<br>";
    }
    
    public function GetFigureData()
    {
        $this->ladoUno = $this->_vertice3->GetX() - $this->_vertice1->GetX();
        $this->ladoDos = $this->_vertice3->GetY() - $this->_vertice1->GetY();
        $this->area = $this->ladoUno * $this->ladoDos;
        $this->perimetro = ($this->ladoUno * 2) + ($this->ladoDos * 2);

        $info = '';

        $info .= "<h1>Lado 1: " . $this->ladoUno . "</h1>";
        $info .= "<h1>Lado 2: " . $this->ladoDos . "</h1>";
        $info .= "<h1>Área: " . $this->area . "</h1>";
        $info .= "<h1>Perímetro: " . $this->perimetro . "</h1>";

        echo $info;
    }

}
?>