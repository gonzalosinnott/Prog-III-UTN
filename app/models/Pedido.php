<?php

require_once './utils/enums.php';
class Pedido 
{
    public $id_pedido;
    public $id_mesa;
    public $id_mozo;
    public $cliente;
    public $estado; //1 - "con cliente esperando pedido” , 2 - ”con cliente comiendo”, 3- “con cliente pagando” y 4- “cerrada”.
    public $created_at;
    public $hora_entrega;
    public $precio_final;
    public $activo;
       
    
    public static function Alta($pedido)
    {
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO  pedido (id_mesa, id_mozo, cliente, estado, created_at, hora_entrega, activo)  VALUES (:id_mesa, :id_mozo, :cliente, :estado, :created_at, :hora_entrega, :activo)");
            $consulta->bindValue(':id_mesa', $pedido->id_mesa, PDO::PARAM_STR);
            $consulta->bindValue(':id_mozo', $pedido->id_mozo, PDO::PARAM_STR);
            $consulta->bindValue(':cliente', $pedido->cliente, PDO::PARAM_STR); 
            $consulta->bindValue(':estado', '1', PDO::PARAM_STR); 
            $consulta->bindValue(':activo', '1', PDO::PARAM_STR);
            $fecha = new DateTime(date("d-m-Y H:i:s"));
            $consulta->bindValue(':created_at', date_format($fecha, 'Y-m-d H:i:s'));
            $fecha_prevista = $fecha->modify('+'.$pedido->tiempo_estimado.' minutes');
            $consulta->bindValue(':hora_entrega', date_format($fecha_prevista,'Y-m-d H:i:s'), PDO::PARAM_STR);
            return $consulta->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } 
    
    public function MostrarPedidos()
    {
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta('SELECT * FROM pedido WHERE activo = :estado');
            $consulta->bindValue(':estado', AltaBaja::ALTA->value, PDO::PARAM_STR); 
            $consulta->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");
    }

    public static function ObtenerPorEstado($estado)
    {
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedido WHERE estado = :estado AND activo = :activo");
            $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
            $consulta->bindValue(':activo', AltaBaja::ALTA->value, PDO::PARAM_STR); 
            $consulta->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");
    }

    public static function ObtenerPorId($id_pedido)
    {
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedido WHERE id_pedido = :id_pedido");
            $consulta->bindValue(':id_pedido', $id_pedido, PDO::PARAM_STR);
            $consulta->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $consulta->fetchObject('Pedido');
    }

    public function ModificarPedido($pedido)
    {
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta('UPDATE pedido SET id_mesa = :id_mesa, id_mozo = :id_mozo, cliente = :cliente, hora_entrega = :hora_entrega WHERE id_pedido = :id_pedido');
            $consulta->bindValue(':id_pedido', $pedido->id_pedido, PDO::PARAM_INT);
            $consulta->bindValue(':id_mesa', $pedido->id_mesa, PDO::PARAM_STR);
            $consulta->bindValue(':id_mozo', $pedido->id_mozo, PDO::PARAM_INT);
            $consulta->bindValue(':cliente', $pedido->cliente, PDO::PARAM_INT);
            $consulta->bindValue(':hora_entrega', $pedido->hora_entrega, PDO::PARAM_INT);
            $consulta->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function CambiarEstadoPedido($pedido, $activo)
    {
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta('UPDATE pedido SET activo = :activo WHERE id_pedido = :id_pedido');
            $consulta->bindValue(':id_pedido', $pedido->id_pedido, PDO::PARAM_STR);
            $consulta->bindValue(':activo', $activo, PDO::PARAM_INT);
            $consulta->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

?>