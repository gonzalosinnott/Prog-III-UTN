<?php

/* 

Aplicación No 10 (Arrays de Arrays)
Realizar las líneas de código necesarias para generar un Array asociativo y otro indexado que
contengan como elementos tres Arrays del punto anterior cada uno. Crear, cargar y mostrar los
Arrays de Arrays.

Sinnott Segura Gonzalo
*/

    $keys = array('color', 'marca', 'trazo', 'precio');
    $keysarray = array('lapicera1', 'lapicera2', 'lapicera3');

    $datos1 = array('rojo', 'bic', 'fino', 50);
    $datos2 = array('verde', 'parker', 'grueso', 200);
    $datos3 = array('negro', 'bic', 'grueso', 25);

    $lapicera1 = array_combine($keys, $datos1);
    $lapicera2 = array_combine($keys, $datos2);
    $lapicera3 = array_combine($keys, $datos3);

    $arrayIndexado = array($lapicera1, $lapicera2, $lapicera3);
    $arrayAsociativo = array_combine($keysarray,$arrayIndexado);

    $vec = array($arrayIndexado, $arrayAsociativo);

    foreach($vec as $data)
    {
        echo "<pre>";
        print_r($data);
    }     
?>