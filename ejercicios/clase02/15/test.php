<?php
/*

Gonzalo Sinnott Segura

*/

include "rectangulo.php";
include "triangulo.php";

$rectangulo = New Rectangulo(4, 6, "red");

$rectangulo->Dibujar();
$rectangulo->ToString();

$triangulo = New Triangulo(6, 6, "blue");

$triangulo->Dibujar();
$triangulo->ToString();

?>