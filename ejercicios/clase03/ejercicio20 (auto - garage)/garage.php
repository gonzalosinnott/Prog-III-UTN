<?php
/* 
Crear la clase Garage que posea como atributos privados:

_razonSocial (String)
_precioPorHora (Double)
_autos (Autos[], reutilizar la clase Auto del ejercicio anterior)

Realizar un constructor capaz de poder instanciar objetos pasándole como parámetros:

i. La razón social.
ii. La razón social, y el precio por hora.

Realizar un método de instancia llamado “MostrarGarage”, que no recibirá parámetros y
que mostrará todos los atributos del objeto.
Crear el método de instancia “Equals” que permita comparar al objeto de tipo Garaje con un
objeto de tipo Auto. Sólo devolverá TRUE si el auto está en el garaje.
Crear el método de instancia “Add” para que permita sumar un objeto “Auto” al “Garage”
(sólo si el auto no está en el garaje, de lo contrario informarlo).
Ejemplo: $miGarage->Add($autoUno);
Crear el método de instancia “Remove” para que permita quitar un objeto “Auto” del
“Garage” (sólo si el auto está en el garaje, de lo contrario informarlo).
Ejemplo: $miGarage->Remove($autoUno);

Crear un método de clase para poder hacer el alta de un Garage y, guardando los datos en un
archivo garages.csv.

Hacer los métodos necesarios en la clase Garage para poder leer el listado desde el archivo
garage.csv

Se deben cargar los datos en un array de garage.

En testGarage.php, crear autos y un garage. Probar el buen funcionamiento de todos
los métodos.
*/

include_once "auto.php";

class Garage
{
    private $_razonSocial;
    private $_precioPorHora;
    private $_autos;

    public function __construct($razonSocial, $precioPorHora = 200)
    {
        $this->_razonSocial = $razonSocial;
        $this->_precioPorHora = $precioPorHora;
        $this->_autos = array();
    }


    //Realizar un método de instancia llamado “MostrarGarage”, que no recibirá parámetros y
    //que mostrará todos los atributos del objeto.
    public function MostrarGarage()
    {
        echo "<br>Razon Social: " . $this->_razonSocial;
        echo "<br>Precio por hora: " . $this->_precioPorHora;
        echo "<br>Autos: ";
        foreach ($this->_autos as $auto)
        {
            echo "<br>";
            $auto->MostrarAuto($auto);
        }
    }

    //Crear el método de instancia “Equals” que permita comparar al objeto de tipo Garaje con un
    //objeto de tipo Auto. Sólo devolverá TRUE si el auto está en el garaje.
    public function Equals($auto)
    {
        foreach ($this->_autos as $autoGarage)
        {
            if ($autoGarage == $auto)
            {
                return true;
            }
        }
        return false;
    }

    //Crear el método de instancia “Add” para que permita sumar un objeto “Auto” al “Garage”
    //(sólo si el auto no está en el garaje, de lo contrario informarlo).
    public function Add($auto)
    {
        if ($this->Equals($auto))
        {
            $message =  "El auto ya esta en el garage" . "<br>";
            echo $message;
        }
        else
        {
            array_push($this->_autos, $auto);
            $message = "Auto agregado: " . "<br>"; 
            echo $message;
            $auto->MostrarAuto($auto);
        }
        
    }

    public function AddToObject($auto)
    {
        if (!$this->Equals($auto))
        {
            array_push($this->_autos, $auto);
        }        
    }

    //Crear el método de instancia “Remove” para que permita quitar un objeto “Auto” del
    //“Garage” (sólo si el auto está en el garaje, de lo contrario informarlo).
    public function Remove($auto)
    {
        if ($this->Equals($auto))
        {
            $key = array_search($auto, $this->_autos);             
            array_splice($this->_autos, $key, 1);            
            $message =  "Auto eliminado <br>";
        }     
        else
        {
            $message = "El auto no esta en el garage <br>";
        }

        echo $message;
    }


    //Crear un método de clase para poder hacer el alta de un Garage y, guardando los datos en un archivo garages.csv.
    public function GarageData()
    {
        $stringCars = $this->_razonSocial . "," . $this->_precioPorHora .PHP_EOL;
   
        foreach ($this->getAutos() as $auto)
        {
            $stringCars .= $auto->AutoData();
        }            
    
        return $stringCars;
    }

    public function getAutos()
    {
        return $this->_autos;
    }    
        
    public static function EscrituraCSV($garage, $file="Garage.csv")
    {
        $file = fopen($file, "w");
        fwrite($file, $garage->GarageData()); 
        fclose($file);
    }


    //Hacer los métodos necesarios en la clase Garage para poder leer el listado desde el archivo garage.csv

    public static function LecturaCSV($file="Garage.csv"): Garage
    {
        $garage = new Garage("Garage", 200);
        $counter = 0;
        $file = fopen($file, "r");
        while (!feof($file))
        {
            $line = fgets($file);
            if (!empty($line))
            {
                $line = str_replace(PHP_EOL, '', $line);
                $data = explode(',', $line);
                if($counter == 0)
                {
                    $garage = new Garage($data[0], $data[1]);
                }
                else
                {
                    $auto = new Auto($data[0], $data[1], $data[2], $data[3]);
                    $garage->AddToObject($auto);
                }
                $counter++;
            
            }
        }         
        fclose($file);

        return $garage;
    }
}
?>