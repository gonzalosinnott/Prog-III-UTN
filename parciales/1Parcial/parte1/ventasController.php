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
}
?>