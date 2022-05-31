<?php

/* 
Aplicación No 2 (Mostrar fecha y estación)
Obtenga la fecha actual del servidor (función date) y luego imprímala dentro de la página con distintos formatos (seleccione los formatos que más le guste). 
Además indicar que estación del año es. Utilizar una estructura selectiva múltiple.

Sinnott Segura Gonzalo
*/

    date_default_timezone_set('America/Argentina/Buenos_Aires');

    $fecha = new DateTime();

    $Otoño = new DateTime('21 March');
    $Invierno = new DateTime('21 June');
    $Primavera = new DateTime('21 September');
    $Verano = new DateTime('21 December');

    $var1 =  'Hoy es: ' . $fecha->format('d-m-Y');
    $var2 =  'Hoy es: ' . $fecha->format('jS F Y');
    $var3 =  'Hoy es: ' . $fecha->format('d/m/Y H:i:s');
    $var4 =  'La hora es: ' . $fecha->format('g:i A');
    
    $fecha = array($var1, $var2, $var3, $var4);

    switch(true)
    {
        case $fecha >= $Primavera && $fecha < $Verano:
            $estacion =  'Es primavera';
            break;
        case $fecha >= $Verano && $fecha < $Otoño:
            $estacion =  'Es verano';
            break;
        case $fecha >= $Otoño && $fecha < $Invierno:
            $estacion =  'Es otoño';
            break;
        default:
            $estacion =  'Es invierno';
    }

    foreach($fecha as $data)
    {
        printf("$data <br>");
    }
    
    echo $estacion;
?>