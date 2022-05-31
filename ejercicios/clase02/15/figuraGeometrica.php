<?php
/*

Aplicación No 15 (Figuras geométricas)
La clase FiguraGeometrica posee: todos sus atributos protegidos, un constructor por defecto,
un método getter y setter para el atributo _color, un método virtual (ToString) y dos
métodos abstractos: Dibujar (público) y CalcularDatos (protegido).
CalcularDatos será invocado en el constructor de la clase derivada que corresponda, su
funcionalidad será la de inicializar los atributos _superficie y _perimetro.
Dibujar, retornará un string (con el color que corresponda) formando la figura geométrica del
objeto que lo invoque (retornar una serie de asteriscos que modele el objeto).
Ejemplo:
* *******
*** *******
***** *******
Utilizar el método ToString para obtener toda la información completa del objeto, y luego
dibujarlo por pantalla.
Jerarquía de clases:

Gonzalo Sinnott Segura
*/

abstract class FiguraGeometrica
{

    protected $_color;
    protected $_perimetro;
    protected $_superficie;

    public function __construct($color = null, $perimetro = null, $superficie = null)
    {
        $this->_color = $color;
        $this->_perimetro = $perimetro;
        $this->_superficie = $superficie;
    }

    public function GetColor()
    {
        return $this->_color;
    }

    public function SetColor($_color)
    {
        $this->_color = $_color;
    }

    public function ToString()
    {
        echo "Color: " . $this->_color . " Perimetro: " . $this->_perimetro . " Superficie: " . $this->_superficie . "<br>";
    }

    public abstract function Dibujar();

    protected abstract function CalcularDatos();
}

?>