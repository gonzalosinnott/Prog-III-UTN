<?php

/* 

Aplicación No 9 (Arrays asociativos)
Realizar las líneas de código necesarias para generar un Array asociativo $lapicera, que
contenga como elementos: ‘color’, ‘marca’, ‘trazo’ y ‘precio’. Crear, cargar y mostrar tres
lapiceras.

Sinnott Segura Gonzalo
*/

    $keys = array('color', 'marca', 'trazo', 'precio');

    $datos1 = array('rojo', 'bic', 'fino', 50);
    $datos2 = array('verde', 'parker', 'grueso', 200);
    $datos3 = array('negro', 'bic', 'grueso', 25);

    $lapicera1 = array_combine($keys, $datos1);
    $lapicera2 = array_combine($keys, $datos2);
    $lapicera3 = array_combine($keys, $datos3);

    $vec = array($lapicera1, $lapicera2, $lapicera3);

    foreach($vec as $data)
    {
        echo "<pre>";
        print_r($data);
    }
?>