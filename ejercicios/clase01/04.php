<?php

/* 
Aplicación No 4 (Calculadora)
Escribir un programa que use la variable $operador que pueda almacenar los símbolos
matemáticos: ‘+’, ‘-’, ‘/’ y ‘*’; y definir dos variables enteras $op1 y $op2. De acuerdo al
símbolo que tenga la variable $operador, deberá realizarse la operación indicada y mostrarse el
resultado por pantalla.

Sinnott Segura Gonzalo
*/

    function calcular($numero1, $numero2, $operador)
    {
        switch ($operador)
        {
            case '+':
                $resultado = "La suma de $numero1 y $numero2 es: " . ($numero1 + $numero2);
                break;
            case '-':
                $resultado = "La resta de $numero1 y $numero2 es: ". ($numero1 - $numero2);
                break;
            case '/':
                if($numero2 == 0)
                {
                    $resultado = 'No se puede dividir por 0';
                }
                else
                {
                    $resultado = "La division de $numero1 y $numero2 es: ". ($numero1 / $numero2);
                }
                break;
            case '*':
                $resultado = "La mutiplicacion de $numero1 y $numero2 es: ". ($numero1 * $numero2);
                break;
            default:
                $resultado = 'Operador no valido';
                break;      
        }

        echo $resultado. "<br>";
    }

    calcular(5, 2, '+');
    calcular(5, 2, '-');
    calcular(5, 2, '/');
    calcular(5, 2, '*');
    calcular(5, 2, '%');
    calcular(5, 0, '/');
?>