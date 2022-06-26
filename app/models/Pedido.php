<?php


class Pedido 
{
    public $id;
    public $id_mesa;
    public $cliente;
    public $estado; //1 - "con cliente esperando pedido” , 2 - ”con cliente comiendo”, 3- “con cliente pagando” y 4- “cerrada”.
    public $created_at;
    public $tiempo_estimado;
    public $precio_final;
    public $activo;
       
    
    public static function Alta($pedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO  pedido (id_mesa, cliente, estado, created_at, hora_entrega, activo)  VALUES (:id_mesa, :cliente, :estado, :created_at, :hora_entrega, :activo)");
        $consulta->bindValue(':id_mesa', $pedido->id_mesa, PDO::PARAM_STR);
        $consulta->bindValue(':cliente', $pedido->cliente, PDO::PARAM_STR); 
        $consulta->bindValue(':estado', '1', PDO::PARAM_STR); 
        $consulta->bindValue(':activo', '1', PDO::PARAM_STR);
        $fecha = new DateTime(date("d-m-Y H:i:s"));
        $consulta->bindValue(':created_at', date_format($fecha, 'Y-m-d H:i:s'));
        $fecha_prevista = $fecha->modify('+'.$pedido->tiempo_estimado.' minutes');
        $consulta->bindValue(':hora_entrega', date_format($fecha_prevista,'Y-m-d H:i:s'), PDO::PARAM_STR);
        return $consulta->execute();
    }   
}

?>