<?php

/* 

Aplicación No 7 (Mostrar impares)
Generar una aplicación que permita cargar los primeros 10 números impares en un Array.
Luego imprimir (utilizando la estructura for) cada uno en una línea distinta (recordar que el
salto de línea en HTML es la etiqueta <br/>). Repetir la impresión de los números utilizando
las estructuras while y foreach.

Sinnott Segura Gonzalo
*/

    $j = 0;

    for($i = 0; $j<10; $i++)
    {
        if($i%2 != 0)
        {
            $array[$j] = $i;
            $j++;
        }
    }

    printf("Primeros 10 numeros impares - Metodo For: <br>");

    for($i = 0; $i<count($array); $i++)
    {
        printf("$array[$i] <br>");
    }

    echo "<br>";

    printf("Primeros 10 numeros impares - Metodo ForEach: <br>");

    foreach($array as $numeroImpar)
    {
        printf("$numeroImpar <br>");
    }

    echo "<br>";

    printf("Primeros 10 numeros impares - Metodo While: <br>");

    $i = 0;

    while($i < count($array))
    {
        printf("$array[$i] <br>");
        $i++;
    }   
?>