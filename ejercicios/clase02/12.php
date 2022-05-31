<?php

/* 

Aplicación No 12 (Invertir palabra)
Realizar el desarrollo de una función que reciba un Array de caracteres y que invierta el orden de las letras del Array.
Ejemplo: Se recibe la palabra “HOLA” y luego queda “ALOH”.

Sinnott Segura Gonzalo
*/

    $array = array('H', 'O', 'L', 'A');

    //Metodo array_reverse
    $reversed =  array_reverse($array);
    
    //Metodo For
    $reversedArray;
    
    for($i=count($array)-1; $i > -1; $i--)
    { 
        $reversedArray[]= $array[$i];     
    }

    echo "<pre>";
    print_r($reversed);
    echo "<pre>";
    print_r($reversedArray); 
      
?>