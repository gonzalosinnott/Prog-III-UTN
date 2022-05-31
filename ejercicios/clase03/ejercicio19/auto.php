<?php
/*
Aplicación No 19 (Auto)
Realizar una clase llamada “Auto” que posea los siguientes atributos privados:

_color (String)
_precio (Double)
_marca (String).
_fecha (DateTime)

Realizar un constructor capaz de poder instanciar objetos pasándole como parámetros:

i. La marca y el color.
ii. La marca, color y el precio.
iii. La marca, color, precio y fecha.

Realizar un método de instancia llamado “AgregarImpuestos”, que recibirá un doble por
parámetro y que se sumará al precio del objeto.
Realizar un método de clase llamado “MostrarAuto”, que recibirá un objeto de tipo “Auto”
por parámetro y que mostrará todos los atributos de dicho objeto.
Crear el método de instancia “Equals” que permita comparar dos objetos de tipo “Auto”. Sólo
devolverá TRUE si ambos “Autos” son de la misma marca.
Crear un método de clase, llamado “Add” que permita sumar dos objetos “Auto” (sólo si son
de la misma marca, y del mismo color, de lo contrario informarlo) y que retorne un Double con
la suma de los precios o cero si no se pudo realizar la operación.
Ejemplo: $importeDouble = Auto::Add($autoUno, $autoDos);

Crear un método de clase para poder hacer el alta de un Auto, guardando los datos en un
archivo autos.csv.
Hacer los métodos necesarios en la clase Auto para poder leer el listado desde el archivo
autos.csv
Se deben cargar los datos en un array de autos.

Gonzalo Sinnott Segura
*/

date_default_timezone_set('America/Argentina/Buenos_Aires');

class Auto
{
    private $_color;
    private $_precio;
    private $_marca;
    private $_fecha;



    //Constructor de la clase auto
    public function __construct($color=null, $precio=null, $marca=null, $fecha=null)
    {
        $this->_color = $color;
        $this->_marca = $marca;
        $this->_precio = $precio;
        $this->_fecha = $fecha;
    }

    //Realizar un método de instancia llamado “AgregarImpuestos”, que recibirá un doble por
    //parámetro y que se sumará al precio del objeto.
    public function AgregarImpuesto($_impuesto)
    {
        $this->_precio += $_impuesto;
    }

    //Realizar un método de clase llamado “MostrarAuto”, que recibirá un objeto de tipo “Auto”
    //por parámetro y que mostrará todos los atributos de dicho objeto.
    public static function MostrarAuto(Auto $auto)
    {
        echo "<pre>";
            print_r($auto); 
        echo "</pre>";
    }

    //Crear el método de instancia “Equals” que permita comparar dos objetos de tipo “Auto”. Sólo
    //devolverá TRUE si ambos “Autos” son de la misma marca.
    public function Equals($auto1, $auto2)
    {
        if($auto1->_marca == $auto2->_marca)
        {
            return true; 
        }
        else
        {
            return false;
        }
    }
    
    //Crear un método de clase, llamado “Add” que permita sumar dos objetos “Auto” (sólo si son
    //de la misma marca, y del mismo color, de lo contrario informarlo) y que retorne un Double con
    //la suma de los precios o cero si no se pudo realizar la operación.
    public static function Add($auto1, $auto2)
    {   
        //$marca = $this->Equals($auto1, $auto2);

        if($auto1->_marca == $auto2->_marca && $auto1->_color == $auto2->_color)
        {
            return $auto1->_precio + $auto2->_precio;
        }
        else
        {
            echo "No coincide marca y color"."</br>";
            return 0;
        }
    }
    
    
    //Crear un método de clase para poder hacer el alta de un Auto, guardando los datos en un archivo autos.csv.

    public function AutoData()
    {
        return $this->_color . "," . $this->_marca . "," . $this->_precio . "," . $this->_fecha.PHP_EOL;
    }

    public static function EscrituraCSV($arrayAuto, $fileName='autos.csv')
    {
        $success = 0;
        $file = fopen($fileName, 'w');
        foreach ($arrayAuto as $auto)
        {
            $success += fwrite($file, $auto->AutoData() . PHP_EOL);
        }
        fclose($file);

        return $success;
    }
    
    //Hacer los métodos necesarios en la clase Auto para poder leer el listado desde el archivo autos.csv
    public static function LecturaCSV(string $path, array $array)
    {                
        $archivos = fopen($path, "r");
        while (!feof($archivos))
        {
            $buffer = fgets($archivos);
            if ($buffer != false)
            {
                $bufferObject = explode("<br>", $buffer);
                foreach ($bufferObject as $dato)
                {
                    $aux = explode(",", $dato);                    
                    if (count($aux) == 2)
                    {
                        $auxAuto = new Auto($aux[0], $aux[1]);
                    }
                    else if (count($aux) == 3)
                    {
                        $auxAuto = new Auto($aux[0], $aux[1], $aux[2]);
                    } else if (count($aux) == 4)
                    {
                        $auxAuto = new Auto($aux[0], $aux[1], $aux[2], new DateTime($aux[3]));
                    }
                    array_push($array, $auxAuto);                   
                }
            }
        }
        fclose($archivos);
        return $array;
    }    
}
?>