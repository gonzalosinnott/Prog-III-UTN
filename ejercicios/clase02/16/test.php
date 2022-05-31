<?php
/*

Desarrollar una aplicación que muestre todos los datos del rectángulo y lo dibuje en la página.

Gonzalo Sinnott Segura

*/

include "rectangulo.php";

$rectangulo = new Rectangulo(new Punto(10, 5), new Punto(40, 32));

$rectangulo->Dibujar();
$rectangulo->GetFigureData();


?>