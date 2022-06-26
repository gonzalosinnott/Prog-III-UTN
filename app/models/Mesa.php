<?php


//include_once '../app/models/Comanda.php';

class Mesa
{

    public $id_mesa;
    public $estado_mesa;

    public function CrearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta('INSERT INTO mesa (id_mesa, estado_mesa) VALUES (:id_mesa, :estado_mesa)');
        $consulta->bindValue(':id_mesa', $this->id_mesa, PDO::PARAM_STR);
        $consulta->bindValue(':estado_mesa', $this->estado_mesa, PDO::PARAM_INT);
        $consulta->execute();
        return true;
    }

    public function ModificarMesa($mesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta('UPDATE mesa SET estado_mesa = :estado_mesa WHERE id_mesa = :id_mesa');
        $consulta->bindValue(':id_mesa', $mesa->id_mesa, PDO::PARAM_STR);
        $consulta->bindValue(':estado_mesa', $mesa->estado_mesa, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function CambiarEstadoMesa($mesa, $estado_mesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta('UPDATE mesa SET estado_mesa = :estado_mesa WHERE id_mesa = :id_mesa');
        $consulta->bindValue(':id_mesa', $mesa->id_mesa, PDO::PARAM_STR);
        $consulta->bindValue(':estado_mesa', $estado_mesa, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function MostrarMesas()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta('SELECT * FROM mesa WHERE estado_mesa != 6');
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function ObtenerPorId($id_mesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesa WHERE id_mesa = :id_mesa");
        $consulta->bindValue(':id_mesa', $id_mesa, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }

    public static function ObtenerPorEstado($estado)
    {
        
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesa WHERE estado_mesa = :estado");
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->execute();
        
        return $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");
    }

    public static function ObtenerEstadoMesaLibre($id_mesa)
    {
        $estado_mesa = 1;
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta('SELECT * FROM mesa WHERE id_mesa = :id_mesa AND estado_mesa = :estado_mesa');
        $consulta->bindValue(':id_mesa', $id_mesa, PDO::PARAM_STR);
        $consulta->bindValue(':estado_mesa', $estado_mesa , PDO::PARAM_STR);

        return $consulta->execute();
    }

    /*

    public static function getMesa_MasUsada()
    {
        $mesas = self::mostrarMesas();
        $comandas = Comanda::mostrarComandas();
        $mesasUsadas = array();
        //iterate comandas and find what is the most used mesa
        if (count($comandas) > 0) {

            foreach ($mesas as $mesa) {
                $cant = 0;
                $mesasUsadas[$mesa->id_mesa] = $cant;
                foreach ($comandas as $comanda) {
                    if ($comanda->id_mesa == $mesa->id_mesa) {
                        $mesasUsadas[$mesa->id_mesa]++;
                    }
                }
            }
            $max = max($mesasUsadas);
            foreach ($mesasUsadas as $key => $value) {
                if ($value == $max) {
                    echo ("<br>Mesa mas usada: " . $key . PHP_EOL);
                }
            }
        } else {
            echo ("<br>No hay pedidos registrados");
        }
    }
                                 METRICAS ******************************************
    
    // mas
    //1 menos
    public static function getMesa_MasMenosUsada_Fecha($consulta, $fecha1, $fecha2)
    {
        $mesas = self::mostrarMesas();
        $comandas = Comanda::mostrarComandas_Fecha($fecha1, $fecha2);

        if (count($comandas) > 0) {

            if ($consulta == 0) {
                //from comandas i need to know which comanda->id_mesa is the most used
                $mesasUsadas = array();
                foreach ($mesas as $mesa) {
                    $cant = 0;
                    $mesasUsadas[$mesa->id_mesa] = $cant;
                    foreach ($comandas as $comanda) {
                        if ($comanda->id_mesa == $mesa->id_mesa) {
                            $mesasUsadas[$mesa->id_mesa]++;
                        }
                    }
                }
                $max = max($mesasUsadas);
                foreach ($mesasUsadas as $key => $value) {
                    if ($value == $max) {
                        echo ("<br>Mesa mas usada: " . $key . PHP_EOL);
                    }
                }
            } else {
                //from comandas i need to know which comanda->id_mesa is the less used
                $mesasUsadas = array();
                foreach ($mesas as $mesa) {
                    $cant = 0;
                    $mesasUsadas[$mesa->id_mesa] = $cant;
                    foreach ($comandas as $comanda) {
                        if ($comanda->id_mesa == $mesa->id_mesa) {
                            $mesasUsadas[$mesa->id_mesa]++;
                        }
                    }
                }
                $min = min($mesasUsadas);
                foreach ($mesasUsadas as $key => $value) {
                    if ($value == $min) {
                        echo ("<br>Mesa menos usada: " . $key . PHP_EOL);
                    }
                }
            }
        } else {
            echo ("<br>Las mesas no registran pedidos en ese periodo");
        }
    }

    */   
}
