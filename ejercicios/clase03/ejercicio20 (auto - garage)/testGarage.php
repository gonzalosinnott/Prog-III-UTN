<?php
/* 
Aplicación No 18 (Auto - Garage)

En testGarage.php, crear autos y un garage. Probar el buen funcionamiento de todos los
métodos.

Crear un método de clase para poder hacer el alta de un Garage y, guardando los datos en un
archivo garages.csv.

Hacer los métodos necesarios en la clase Garage para poder leer el listado desde el archivo
garage.csv

Se deben cargar los datos en un array de garage.

Sinnott Segura Gonzalo

*/

require "garage.php";
include_once "auto.php";

$garage = new Garage("Garage Php");
$auto1 = new Auto("Chevrolet Onix", "Azul", "75200");
$auto2 = new Auto("Ford Ka", "Rojo", "50200");
$auto3 = new Auto("Renault Logan", "Negro", "30500","25/02/1990");

$addCar1 = $garage->Add($auto1);
$addCar2 = $garage->Add($auto2);
$addCar3 = $garage->Add($auto3);
$addAutoRepetido= $garage->Add($auto3);

$garage->MostrarGarage();

$garage->Remove($auto3);

$garage->MostrarGarage();

$garage->Remove($auto3);

echo "<br>Escritura CSV....<br>";
Garage::EscrituraCSV($garage);

echo "<br>LecturaCSV<br>";

$garage = Garage::LecturaCSV();

$garage->MostrarGarage();

?>