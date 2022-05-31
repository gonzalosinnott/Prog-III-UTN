<?php

/* 
Aplicación No 5 (Números en letras)
Realizar un programa que en base al valor numérico de una variable $num, pueda mostrarse
por pantalla, el nombre del número que tenga dentro escrito con palabras, para los números
entre el 20 y el 60.
Por ejemplo, si $num = 43 debe mostrarse por pantalla “cuarenta y tres”.

Sinnott Segura Gonzalo
*/

    function numeroALetras($numero)
    {
        if($numero > 19 && $numero <61)
        {
            $array = str_split($numero);
        
            switch($array[0])
            {
                case "2":
                    $decena = "Veinte";
                    break;
                case "3":
                    $decena = "Treinta";
                    break;
                case "4":
                    $decena = "Cuarenta";
                    break;
                case "5":
                    $decena = "Cincuenta";
                    break;
                case "6":
                    $decena = "Sesenta";
                    break;
                default: 
                    $decena = "";
            }

            switch($array[1])
            {
                case "1":
                    $unidad = "y Uno";
                    break;
                case "2":
                    $unidad = "y Dos";
                    break;
                case "3":
                    $unidad = "y Tres";
                    break;
                case "4":
                    $unidad = "y Cuatro";
                    break;
                case "5":
                    $unidad = "y Cinco";
                    break;
                case "6":
                    $unidad = "y Seis";
                    break;
                case "7":
                    $unidad = "y Siete";
                    break;
                case "8":
                    $unidad = "y Ocho";
                    break;
                case "9":
                    $unidad = "y Nueve";
                    break;
                default: 
                    $unidad = ""; 
            }

            $resultado = "$decena $unidad";
        }
        else
        {
            $resultado = "Numero fuera de rango";
        }

        echo $resultado."<br>";
        
    }

    numeroALetras(15);
    numeroALetras(20);
    numeroALetras(45);
    numeroALetras(61);
?>