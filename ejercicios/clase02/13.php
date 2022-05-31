<?php

/* 

Aplicación No 13 (Invertir palabra)
Crear una función que reciba como parámetro un string ($palabra) y un entero ($max). La
función validará que la cantidad de caracteres que tiene $palabra no supere a $max y además
deberá determinar si ese valor se encuentra dentro del siguiente listado de palabras válidas:
“Recuperatorio”, “Parcial” y “Programacion”. Los valores de retorno serán:
1 si la palabra pertenece a algún elemento del listado.
0 en caso contrario.

Sinnott Segura Gonzalo
*/

function Check($palabra, $max)
{
    $array = array("Recuperatorio", "Parcial", "Programacion");
    $result = 0;
    if(strlen($palabra) <= $max)
    {
        foreach($array as $string)
        {
          if(strcmp($string, $palabra) == 0) 
          {
            $result = 1;
            break;
          }          
        }
        
    }
    else
    {
        $result = "La palabra supera el maximo de caracteres permitidos";
    }

    echo $result."<br>";
}

Check("Recuperatorio",13)."<br/>";
Check("Recuperatorio",10)."<br/>";
Check("Parcial",7)."<br/>";
Check("Parcial",5)."<br/>";
Check("Programacion",12)."<br/>";
Check("Programacion",10)."<br/>";
Check("Hola",13)."<br/>";
Check("Hola",2)."<br/>";
      
?>