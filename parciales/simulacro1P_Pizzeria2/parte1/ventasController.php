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
            DataController:: guardarFoto($pizza, $imagen, $email, $fechaDeVenta, $pedidoNumero, "venta");
            $output = true;
        }
        return $output;
    }   
}
?>