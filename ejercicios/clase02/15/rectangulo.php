<?php
/*

Gonzalo Sinnott Segura
*/

include_once "figuraGeometrica.php";

class Rectangulo extends FiguraGeometrica
{
    public $_ladoUno;
    public $_ladoDos;

    public function __construct($l1, $l2, $color = null)
    {
        $this->_ladoUno = $l1;
        $this->_ladoDos = $l2;

        if($this->CalcularDatos($l1, $l2))
        {
            $this->_color = $color;
            $this->_perimetro = ($l1 + $l2) * 2;
            $this->_superficie = $l1 * $l2;
        }
    }

    public function CalcularDatos()
    {
       if($this->_ladoUno > 0 && $this->_ladoDos > 0)
       {
           return true;
       }
       else
       {
           return false;
       }
    }
    

    public function Dibujar()
    {
        $base = $this->_ladoUno;
        $altura = $this->_ladoDos;
        $color = $this->GetColor();
        $resultado = "";
        for ($i = 0; $i < $altura; $i++) {
            for ($j = 0; $j < $base; $j++) {
                $resultado .= "*";
            }
            $resultado .= "<br>";
        }
        echo "<h1 style='color:$color'>$resultado</h1>";
    }
}
?>