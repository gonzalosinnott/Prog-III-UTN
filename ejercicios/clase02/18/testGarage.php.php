<?php
/* 
Aplicación No 18 (Auto - Garage)

En testGarage.php, crear autos y un garage. Probar el buen funcionamiento de todos los
métodos.

Sinnott Segura Gonzalo

*/

require "garage.php";
include_once "auto.php";

$garage = new Garage("Garage Php");
$auto1 = new Auto("Chevrolet Onix", "Azul", "75200");
$auto2 = new Auto("Ford Ka", "Rojo", "50200");
$auto3 = new Auto("Renault Logan", "Negro", "30500","25/02/1990");

$garage->Add($auto1);
$garage->Add($auto2);
$garage->Add($auto3);
$garage->Add($auto3);

$garage->MostrarGarage();
$garage->Remove($auto3);

$garage->MostrarGarage();
$garage->Remove($auto3);


?>