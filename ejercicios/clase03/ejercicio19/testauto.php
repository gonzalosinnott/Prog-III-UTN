<?php
/* 

En testAuto.php:
● Crear dos objetos “Auto” de la misma marca y distinto color.
● Crear dos objetos “Auto” de la misma marca, mismo color y distinto precio.
● Crear un objeto “Auto” utilizando la sobrecarga restante.
● Utilizar el método “AgregarImpuesto” en los últimos tres objetos, agregando $ 1500
al atributo precio.
● Obtener el importe sumado del primer objeto “Auto” más el segundo y mostrar el
resultado obtenido.
● Comparar el primer “Auto” con el segundo y quinto objeto e informar si son iguales o
no.
● Utilizar el método de clase “MostrarAuto” para mostrar cada los objetos impares (1, 3,
5)

Sinnott Segura Gonzalo

*/

include "auto.php";


//Crear dos objetos “Auto” de la misma marca y distinto color.
$auto1 = new Auto("Blanco", "Ford");
$auto2 = new Auto("Negro", "Ford");
//Crear dos objetos “Auto” de la misma marca, mismo color y distinto precio.
$auto3 = new Auto("Blanco", 600000, "Chevrolet");
$auto4 = new Auto("Blanco", 800000, "Chevrolet");
//Crear un objeto “Auto” utilizando la sobrecarga restante.
$auto5 = new Auto("Gris", 700000, "Fiat", "05/09/2021");

//Utilizar el método “AgregarImpuesto” en los últimos tres objetos, agregando $ 1500 al atributo precio.
$auto3->AgregarImpuesto(1500);
$auto4->AgregarImpuesto(1500);
$auto5->AgregarImpuesto(1500);

//Obtener el importe sumado del primer objeto “Auto” más el segundo y mostrar el resultado obtenido.
$sumar = Auto::Add($auto1, $auto2);
echo "El resultado de la suma de los precios de auto 1 y auto 2 es: $sumar <br>";

//Comparar el primer “Auto” con el segundo y quinto objeto e informar si son iguales o no.
function compare($auto1, $auto2)
{
    if($auto1 == $auto2)
    {
        $result =  "Los autos son iguales". "<br>";
    }
    else
    {
        $result = "Los autos son distintos"."</br>";
    }

    return $result;
}

echo compare($auto1, $auto2);
echo compare($auto1, $auto5); 

//Utilizar el método de clase “MostrarAuto” para mostrar los objetos impares (1,3,5)

Auto::MostrarAuto($auto1);
Auto::MostrarAuto($auto3);
Auto::MostrarAuto($auto5);

//ESCRITURA CSV
$ListadoAutos = array();
array_push($ListadoAutos, $auto1, $auto2, $auto3, $auto4, $auto5);

function writeCSV($Listado)
{
    if(Auto::EscrituraCSV($Listado, "autos.csv")>0)
    {
        $result =  "Se ha guardado el archivo correctamente". "<br>";
    }
    else
    {
        $result =  "No se ha podido guardar el archivo" . "<br>";
    }

    return $result;
}

echo writeCSV($ListadoAutos);

//LECTURA CSV

$autos[]="";

$autosLeidos = Auto::LecturaCSV("autos.csv",$autos);

foreach ($autosLeidos as $auto)
{
    echo "<pre>";
        print_r($auto); 
    echo "</pre>";
}

?>