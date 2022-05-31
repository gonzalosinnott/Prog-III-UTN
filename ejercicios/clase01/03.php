<?php

/* 
Aplicación No 3 (Obtener el valor del medio)
Dadas tres variables numéricas de tipo entero $a, $b y $c realizar una aplicación que muestre
el contenido de aquella variable que contenga el valor que se encuentre en el medio de las tres
variables. De no existir dicho valor, mostrar un mensaje que indique lo sucedido.
Ejemplo 1: $a = 6; $b = 9; $c = 8; => se muestra 8.
Ejemplo 2: $a = 5; $b = 1; $c = 5; => se muestra un mensaje “No hay valor del medio”

Sinnott Segura Gonzalo
*/

    middleValue(1,5,3);
    middleValue(5,1,5);
    middleValue(3,5,1);
    middleValue(3,1,5);
    middleValue(5,3,1);
    middleValue(1,5,1); 

    function middleValue ($a, $b, $c)
    {
        if(($a>$b && $a<$c) || ($a>$c && $a<$b))
            $valor = $a;
        elseif (($b>$c && $b<$a) || ($b>$a && $b<$c))
            $valor = $b;    
        elseif (($c>$a && $c<$b) || ($c>$b && $c<$a))
            $valor = $c;   
        else
            $valor ="No hay valor del medio";

        printf(" Valores a analizar: $a, $b y $c <br> El valor del medio es: $valor <br> <br>");        
    }

?>