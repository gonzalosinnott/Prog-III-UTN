<?php

require_once './utils/enums.php';
require_once './models/Producto.php';

class Comanda 
{
    public $id_comanda;
    public $codigo_pedido;
    public $id_producto;
    public $cantidad;
    public $estado; 
    public $precio;
    public $activo;
       
    
    public static function Alta($comanda)
    {
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO  comanda (codigo_pedido, id_producto, cantidad, estado, precio, id_sector, activo)  VALUES (:codigo_pedido, :id_producto, :cantidad, :estado, :precio, :id_sector, :activo)");

            $producto = Producto::ObtenerPorId($comanda->id_producto);
            $precioTotal = $producto->precio * $comanda->cantidad;
            $id_sector = $producto->id_sector;

            $consulta->bindValue(':codigo_pedido', $comanda->codigo_pedido, PDO::PARAM_STR);
            $consulta->bindValue(':id_producto', $comanda->id_producto, PDO::PARAM_STR);
            $consulta->bindValue(':cantidad', $comanda ->cantidad, PDO::PARAM_STR); 
            $consulta->bindValue(':estado', EstadoComanda::PENDIENTE->value, PDO::PARAM_STR);
            $consulta->bindValue(':precio', $precioTotal, PDO::PARAM_STR); 
            $consulta->bindValue(':id_sector', $id_sector, PDO::PARAM_STR); 
            $consulta->bindValue(':activo', AltaBaja::ALTA->value, PDO::PARAM_STR);
            return $consulta->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        return $consulta->execute();
    } 
    
    public static function MostrarComandas()
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

    public static function ObtenerPorIdPedido($codigo_pedido)
    {
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM comanda WHERE codigo_pedido = :codigo_pedido");
            $consulta->bindValue(':codigo_pedido', $codigo_pedido, PDO::PARAM_STR);
            $consulta->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $consulta->fetchAll(PDO::FETCH_CLASS, "Comanda");
    }

    public static function ObtenerPorIdPedidoEstado($codigo_pedido, $estado)
    {
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM comanda WHERE codigo_pedido = :codigo_pedido AND estado != :estado");
            $consulta->bindValue(':codigo_pedido', $codigo_pedido, PDO::PARAM_STR);
            $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
            $consulta->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $consulta->fetchAll(PDO::FETCH_CLASS, "Comanda");
    }


    public function ModificarComanda($comanda)
    {
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta('UPDATE comanda SET codigo_pedido = :codigo_pedido, id_producto = :id_producto, cantidad = :cantidad, precio = :precio WHERE id_comanda = :id_comanda');

            $producto = Producto::ObtenerPorId($comanda->id_producto);
            $precioTotal = $producto->precio * $comanda->cantidad;

            $consulta->bindValue(':id_comanda', $comanda->id_comanda, PDO::PARAM_INT);
            $consulta->bindValue(':codigo_pedido', $comanda->codigo_pedido, PDO::PARAM_STR);
            $consulta->bindValue(':id_producto', $comanda->id_producto, PDO::PARAM_INT);
            $consulta->bindValue(':cantidad', $comanda->cantidad, PDO::PARAM_INT);
            $consulta->bindValue(':precio', $precioTotal, PDO::PARAM_STR); 
            return $consulta->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function CambiarActivoComanda($comanda, $activo)
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

    public static function CambiarEstadoComanda($comanda, $estado)
    {
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta('UPDATE comanda SET estado = :estado WHERE id_comanda = :id_comanda');
            $consulta->bindValue(':id_comanda', $comanda->id_comanda, PDO::PARAM_STR);
            $consulta->bindValue(':estado', $estado, PDO::PARAM_INT);
            return $consulta->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function ObtenerComandaSectorEstado($id_sector, $estado)
    {
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM comanda WHERE id_sector = :id_sector AND estado = :estado");
            $consulta->bindValue(':id_sector', $id_sector, PDO::PARAM_STR);
            $consulta->bindValue(':estado', $estado, PDO::PARAM_STR); 
            $consulta->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $consulta->fetchAll(PDO::FETCH_CLASS, "Comanda");
    }
}

?>