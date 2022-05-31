<?php

/* 
Aplicación No 6 (Carga aleatoria)
Definir un Array de 5 elementos enteros y asignar a cada uno de ellos un número (utilizar la
función rand). Mediante una estructura condicional, determinar si el promedio de los números
son mayores, menores o iguales que 6. Mostrar un mensaje por pantalla informando el
resultado.

Sinnott Segura Gonzalo
*/
    

    for($i = 0; $i < 5; $i++)
    {
        $array[$i] = rand(1,10);  
    }

    $promedio = array_sum($array)/count($array);


    if($promedio < 6)
    {
        $valor= "El promedio es menor a 6";    
    }
    else if($promedio > 6)
    {
        $valor= "El promedio es mayor a 6";
    }
    else
    {
        $valor= "El promedio es igual a 6"; 
    }

    $array = json_encode($array);
    printf("Numeros: $array <br> $valor");
?>