<?php

//$archivo= fopen("saludar.txt","w"); Abre un archivo, si existe borra su contenido

//fclose($archivo); Cierra el archivo

$archivo= fopen("saludar.txt","r+");

//echo fread($archivo,filesize("saludar.txt")); Leo el archivo completo.

$contador = 0;

while(!feof($archivo))
{
    echo fgets($archivo),"<br/>";
    $contador++;
}

echo "cantidad de lineas: ".$contador."</br>";

fclose($archivo);

?>