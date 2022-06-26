<?php

require_once './utils/enums.php';
require_once './models/Producto.php';

class Comanda 
{
    public $id_comanda;
    public $id_pedido;
    public $id_producto;
    public $cantidad;
    public $estado; //1 - "Pendiente” , 2 - ”En Preparacion”, 3- “Listo”.
    public $precio;
    public $activo;
       
    
    public static function Alta($comanda)
    {
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO  comanda (id_pedido, id_producto, cantidad, estado, precio, activo)  VALUES (:id_pedido, :id_producto, :cantidad, :estado, :precio, :activo)");

            $precioUnitario = Producto::ObtenerPrecio($comanda->id_producto);
            $precioTotal = $precioUnitario * $comanda->cantidad;

            $consulta->bindValue(':id_pedido', $comanda->id_pedido, PDO::PARAM_STR);
            $consulta->bindValue(':id_producto', $comanda->id_producto, PDO::PARAM_STR);
            $consulta->bindValue(':cantidad', $comanda ->cantidad, PDO::PARAM_STR); 
            $consulta->bindValue(':estado', EstadoComanda::PENDIENTE->value, PDO::PARAM_STR);
            $consulta->bindValue(':precio', $precioTotal, PDO::PARAM_STR); 
            $consulta->bindValue(':activo', AltaBaja::ALTA->value, PDO::PARAM_STR);
            return $consulta->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } 
    
    public function MostrarComandas()
    {
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta('SELECT * FROM comanda WHERE activo = :estado');
            $consulta->bindValue(':estado', AltaBaja::ALTA->value, PDO::PARAM_STR); 
            $consulta->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $consulta->fetchAll(PDO::FETCH_CLASS, "Comanda");
    }

    public static function ObtenerPorEstado($estado)
    {
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM comanda WHERE estado = :estado AND activo = :activo");
            $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
            $consulta->bindValue(':activo', AltaBaja::ALTA->value, PDO::PARAM_STR); 
            $consulta->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $consulta->fetchAll(PDO::FETCH_CLASS, "Comanda");
    }

    public static function ObtenerPorId($id_comanda)
    {
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM comanda WHERE id_comanda = :id_comanda");
            $consulta->bindValue(':id_comanda', $id_comanda, PDO::PARAM_STR);
            $consulta->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $consulta->fetchObject('Comanda');
    }

    public function ModificarComanda($comanda)
    {
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta('UPDATE comanda SET id_pedido = :id_pedido, id_producto = :id_producto, cantidad = :cantidad, precio = :precio WHERE id_comanda = :id_comanda');

            $precioUnitario = Producto::ObtenerPrecio($comanda->id_producto);
            $precioTotal = $precioUnitario * $comanda->cantidad;

            $consulta->bindValue(':id_comanda', $comanda->id_comanda, PDO::PARAM_INT);
            $consulta->bindValue(':id_pedido', $comanda->id_pedido, PDO::PARAM_STR);
            $consulta->bindValue(':id_producto', $comanda->id_producto, PDO::PARAM_INT);
            $consulta->bindValue(':cantidad', $comanda->cantidad, PDO::PARAM_INT);
            $consulta->bindValue(':precio', $precioTotal, PDO::PARAM_STR); 
            return $consulta->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function CambiarEstadoComanda($comanda, $activo)
    {
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta('UPDATE comanda SET activo = :activo WHERE id_comanda = :id_comanda');
            $consulta->bindValue(':id_comanda', $comanda->id_comanda, PDO::PARAM_STR);
            $consulta->bindValue(':activo', $activo, PDO::PARAM_INT);
            $consulta->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

?>