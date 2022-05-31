<?php

/*
Gonzalo Sinnott Segura
*/

include_once "DBController.php";
include_once "dataController.php";
include_once "heladoController.php";

class VentasController{

    public static function venderHelado($file, $helado, $email, $pedidoNumero, $imagen)
    {

        $venta = Helado::RealizarVenta($file, $helado);
        $output = false;

        if ($venta)
        {
            $date = new DateTime("now");
            $fechaDeVenta = $date->format('Y-m-d');
            $objetoAccesoDato = DBController::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta('INSERT INTO ventaHelados (fechaDeVenta, numeroDePedido, email, sabor, tipo, cantidad) VALUES  (:fechaDeVenta,:pedidoNumero,:email,:sabor,:tipo,:cantidad)');
            $consulta->bindValue(':fechaDeVenta', $fechaDeVenta, PDO::PARAM_STR);
            $consulta->bindValue(':pedidoNumero', $pedidoNumero, PDO::PARAM_STR);
            $consulta->bindValue(':email', $email, PDO::PARAM_INT);
            $consulta->bindValue(':sabor', $helado->sabor, PDO::PARAM_STR);
            $consulta->bindValue(':tipo', $helado->tipo, PDO::PARAM_STR);
            $consulta->bindValue(':cantidad', $helado->cantidad, PDO::PARAM_STR);
            $consulta->execute();
            DataController:: guardarFoto($helado, $imagen, $email, $fechaDeVenta, $pedidoNumero, "venta");
            $output = true;
        }
        return $output;
    }   

    public static function AveriguarCantidadDeHeladosVendidos($fecha) {

    if($fecha == null)     
        $fecha = date('Y-m-d', strtotime("yesterday"));

    $objetoAccesoDato = DBController::dameUnObjetoAcceso();
    $consulta = $objetoAccesoDato->RetornarConsulta("SELECT SUM(cantidad) FROM ventaHelados WHERE fechaDeVenta = :fecha");
    $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
    $consulta->execute();
    $total = $consulta->fetch(PDO::FETCH_NUM);
    return $total[0];
    }

    public static function obtenerVentasEntreFechas($min, $max) {

        $lista = array();        
        $objetoAccesoDato = DBController::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta('SELECT *  FROM ventaHelados WHERE fechaDeVenta BETWEEN :minimo AND :maximo ORDER BY sabor ASC');
        $consulta->bindValue(':minimo', $min, PDO::PARAM_STR);
        $consulta->bindValue(':maximo', $max, PDO::PARAM_STR);
        $consulta->execute();
        $lista = $consulta->fetchAll(PDO::FETCH_ASSOC);
        return $lista;
    }

    public static function obtenerVentasDeUnUsuario($email) {
        $lista = array();
        $objetoAccesoDato = DBController::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta('SELECT *  FROM ventaHelados WHERE email = :email');
        $consulta->bindValue(':email', $email, PDO::PARAM_STR);
        $consulta->execute();
        $lista = $consulta->fetchAll(PDO::FETCH_ASSOC);
        return $lista;
    }

    public static function obtenerVentasDeUnSabor($sabor){
        $lista = array();
        $objetoAccesoDato = DBController::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta('SELECT * FROM ventaHelados WHERE sabor = :sabor');
        $consulta->bindValue(':sabor', $sabor, PDO::PARAM_STR);
        $consulta->execute();
        $lista = $consulta->fetchAll(PDO::FETCH_ASSOC);
        return $lista;
    }

    public static function checkVentaId($numeroDePedido) {
        $objetoAccesoDato = DBController::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta('SELECT * FROM ventaHelados WHERE numeroDePedido = :numeroDePedido');
        $consulta->bindValue(':numeroDePedido', $numeroDePedido, PDO::PARAM_INT);
        $consulta->execute();
        $lista = $consulta->fetchAll(PDO::FETCH_ASSOC);
        return (count($lista) > 0) ? true : false;
    }

    public static function modificarVenta($NumeroDePedido,$Email,$Sabor,$Tipo,$Cantidad)
    {
        $objetoAccesoDato = DBController::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta(
            "UPDATE ventaHelados 
            SET email = :Email,
            Sabor = :Sabor,
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
}
?>