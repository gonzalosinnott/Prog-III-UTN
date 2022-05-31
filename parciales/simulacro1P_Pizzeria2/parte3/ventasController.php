<?php

/*
Gonzalo Sinnott Segura
*/

include_once "DBController.php";
include_once "dataController.php";
include_once "pizzaController.php";

class VentasController{

    public static function venderPizza($file, $pizza, $email, $pedidoNumero, $imagen)
    {

        $venta = Pizza::RealizarVenta($file, $pizza);
        $output = false;

        if ($venta)
        {
            $date = new DateTime("now");
            $fechaDeVenta = $date->format('Y-m-d');
            $objetoAccesoDato = DBController::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta('INSERT INTO ventapizzasv2 (fechaDeVenta, numeroDePedido, email, sabor, tipo, cantidad) VALUES  (:fechaDeVenta,:pedidoNumero,:email,:sabor,:tipo,:cantidad)');
            $consulta->bindValue(':fechaDeVenta', $fechaDeVenta, PDO::PARAM_STR);
            $consulta->bindValue(':pedidoNumero', $pedidoNumero, PDO::PARAM_STR);
            $consulta->bindValue(':email', $email, PDO::PARAM_INT);
            $consulta->bindValue(':sabor', $pizza->sabor, PDO::PARAM_STR);
            $consulta->bindValue(':tipo', $pizza->tipo, PDO::PARAM_STR);
            $consulta->bindValue(':cantidad', $pizza->cantidad, PDO::PARAM_STR);
            $consulta->execute();
            DataController:: guardarFoto($pizza, $imagen, $email, $fechaDeVenta, $pedidoNumero,"venta");
            $output = true;
        }
        return $output;
    }  

    public static function averiguarCantidadDePizzasVendidas($fecha) {

    if($fecha == null)     
        $fecha = date('Y-m-d');

    $objetoAccesoDato = DBController::dameUnObjetoAcceso();
    $consulta = $objetoAccesoDato->RetornarConsulta("SELECT SUM(cantidad) FROM ventapizzasv2 WHERE fechaDeVenta = :fecha");
    $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
    $consulta->execute();
    $total = $consulta->fetch(PDO::FETCH_NUM);
    return $total[0];
    }

    public static function obtenerVentasEntreFechas($min, $max) {
        
        $lista = array();
        
        $objetoAccesoDato = DBController::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta('SELECT *  FROM ventapizzasv2 WHERE fechaDeVenta BETWEEN :minimo AND :maximo ORDER BY sabor ASC');
        $consulta->bindValue(':minimo', $min, PDO::PARAM_STR);
        $consulta->bindValue(':maximo', $max, PDO::PARAM_STR);
        $consulta->execute();
        $lista = $consulta->fetchAll(PDO::FETCH_ASSOC);
        return $lista;
    }

    public static function obtenerVentasDeUnUsuario($email) {
        $lista = array();
        $objetoAccesoDato = DBController::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta('SELECT *  FROM ventapizzasv2 WHERE email = :email');
        $consulta->bindValue(':email', $email, PDO::PARAM_STR);
        $consulta->execute();
        $lista = $consulta->fetchAll(PDO::FETCH_ASSOC);
        return $lista;
    }

    public static function obtenerVentasDeUnSabor($sabor){
        $lista = array();
        $objetoAccesoDato = DBController::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta('SELECT * FROM ventapizzasv2 WHERE sabor = :sabor');
        $consulta->bindValue(':sabor', $sabor, PDO::PARAM_STR);
        $consulta->execute();
        $lista = $consulta->fetchAll(PDO::FETCH_ASSOC);
        return $lista;
    }

    public static function checkVentaId($numeroDePedido) {
        $objetoAccesoDato = DBController::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta('SELECT * FROM ventapizzasv2 WHERE numeroDePedido = :numeroDePedido');
        $consulta->bindValue(':numeroDePedido', $numeroDePedido, PDO::PARAM_INT);
        $consulta->execute();
        $lista = $consulta->fetchAll(PDO::FETCH_ASSOC);
        return (count($lista) > 0) ? true : false;
    }

    public static function modificarVenta($NumeroDePedido,$Email,$Sabor,$Tipo,$Cantidad)
    {
        $objetoAccesoDato = DBController::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta(
            "UPDATE ventapizzasv2 
            SET email = :Email,
            sabor = :Sabor,
            tipo  = :Tipo,
            cantidad = :Cantidad
            WHERE numeroDePedido = :NumeroDePedido");

        $consulta->bindValue(':Email',$Email, PDO::PARAM_STR);
        $consulta->bindValue(':Sabor',$Sabor, PDO::PARAM_STR);
        $consulta->bindValue(':Tipo',$Tipo, PDO::PARAM_STR);
        $consulta->bindValue(':Cantidad',$Cantidad, PDO::PARAM_STR);
        $consulta->bindValue(':NumeroDePedido',$NumeroDePedido, PDO::PARAM_STR);
        $consulta->execute();
        return ($consulta->rowCount() != 0) ? true : false;
    }
    
    public static function borrarVenta($numeroDePedido)
    {
        $venta  =  self::getVenta($numeroDePedido);
        $objetoAccesoDato = DBController::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta('DELETE FROM ventapizzasv2 WHERE numeroDePedido = :numeroDePedido');
        $consulta->bindValue(':numeroDePedido', $numeroDePedido, PDO::PARAM_INT);
        $consulta->execute();
        return ($consulta->rowCount() != 0) ? $venta : null;
    }

    public static function getVenta($numeroDePedido)
    {
        $objetoAccesoDato = DBController::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta('SELECT * FROM ventapizzasv2 WHERE numeroDePedido = :numeroDePedido');
        $consulta->bindValue(':numeroDePedido', $numeroDePedido, PDO::PARAM_INT);
        $consulta->execute();
        $venta = $consulta->fetchAll(PDO::FETCH_ASSOC);
        return $venta;
    }

    public static function devolverVenta($numeroDePedido, $causa, $imagen){

        $venta = self::getVenta($numeroDePedido);

        try{
            self::borrarVenta($numeroDePedido);
            DataController::moverFoto($venta);
            DataController::cargarDevolucion($venta, $causa, $imagen);
            return true;
        }catch(Exception $e){
            return false;
        }
    }
}
?>