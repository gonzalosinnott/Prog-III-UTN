<?php
/*

Gonzalo Sinnott Segura
*/

include_once "figuraGeometrica.php";

class Triangulo extends FiguraGeometrica
{
    public $_altura;
    public $_base;

    public function __construct($altura, $base, $color = null)
    {
        $this->_altura = $altura;
        $this->_base = $base;

        if($this->CalcularDatos($altura, $base))
        {
            $this->_color = $color;
            $this->_perimetro = ($altura + $base) * 2;
            $this->_superficie = ($altura * $base) / 2;
        }
    }

    public function CalcularDatos()
    {
       if($this->_altura > 0 && $this->_base > 0)
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
        $color = $this->GetColor();
        $resultado = "";
        
        $altura = $this->_altura;

        for($i=1;$i<=$altura;$i++){

            for($t = 1;$t <= $altura-$i;$t++)
            {
                $resultado .=  "&nbsp;&nbsp;";
            }

            for($j=1;$j<=$i;$j++)
            {
                $resultado .=  "*&nbsp;&nbsp;";
            }

            $resultado .=  "<br />";
        }

        echo "<h1 style='color:$color'>$resultado</h1>";
    }   
}
?>