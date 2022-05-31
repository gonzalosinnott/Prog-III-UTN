<?php

/*
Gonzalo Sinnott Segura
*/

include_once "DBController.php";
include_once "dataController.php";
include_once "hamburguesaController.php";
include_once "cuponCOntroller.php";

class VentasController{

    public static function venderHamburguesa($file, $hamburguesa, $email, $pedidoNumero, $imagen)
    {
        $output = false;
        $venta = Hamburguesa::RealizarVenta($file, $hamburguesa);
        $array = DataController::leerJson($file);

        if ($venta)
        {
            foreach ($array as $producto){

                if ($producto->getNombre() == $hamburguesa->getNombre() && 
                    $producto->getTipo() == $hamburguesa->getTipo()){
                        $hamburguesa->setPrecio($producto->getPrecio());
                        break;       
                }
            }    
            
            $date = new DateTime("now");
            $fechaDeVenta = $date->format('Y-m-d');
            $objetoAccesoDato = DBController::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta('INSERT INTO ventaHamburguesas (fechaDeVenta, numeroDePedido, email, nombre, tipo, cantidad) VALUES  (:fechaDeVenta,:pedidoNumero,:email,:nombre,:tipo,:cantidad)');
            $consulta->bindValue(':fechaDeVenta', $fechaDeVenta, PDO::PARAM_STR);
            $consulta->bindValue(':pedidoNumero', $pedidoNumero, PDO::PARAM_STR);
            $consulta->bindValue(':email', $email, PDO::PARAM_INT);
            $consulta->bindValue(':nombre', $hamburguesa->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':tipo', $hamburguesa->tipo, PDO::PARAM_STR);
            $consulta->bindValue(':cantidad', $hamburguesa->cantidad, PDO::PARAM_STR);
            $consulta->execute();
            DataController:: guardarFoto($hamburguesa, $imagen, $email, $fechaDeVenta, $pedidoNumero, "venta");
            $precioFinal = $hamburguesa->getPrecio() * $hamburguesa->getCantidad();
            var_dump($precioFinal);
            Cupon::AplicarCupon($precioFinal, $email);            
            $output = true;
        }
        return $output;
    }  

    public static function averiguarCantidadDeHamburguesasVendidas($fecha) {

        if($fecha == null)     
            $fecha = date('Y-m-d', strtotime("yesterday"));
    
        $objetoAccesoDato = DBController::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT SUM(cantidad) FROM ventaHamburguesas WHERE fechaDeVenta = :fecha");
        $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $consulta->execute();
        $total = $consulta->fetch(PDO::FETCH_NUM);
        return $total[0];
        }
    
        public static function obtenerVentasEntreFechas($min, $max) {
            
            $lista = array();        
            $objetoAccesoDato = DBController::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT *  FROM ventaHamburguesas WHERE fechaDeVenta BETWEEN :minimo AND :maximo ORDER BY nombre ASC');
            $consulta->bindValue(':minimo', $min, PDO::PARAM_STR);
            $consulta->bindValue(':maximo', $max, PDO::PARAM_STR);
            $consulta->execute();
            $lista = $consulta->fetchAll(PDO::FETCH_ASSOC);
            return $lista;
        }
    
        public static function obtenerVentasDeUnUsuario($email) {
            $lista = array();
            $objetoAccesoDato = DBController::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT *  FROM ventaHamburguesas WHERE email = :email');
            $consulta->bindValue(':email', $email, PDO::PARAM_STR);
            $consulta->execute();
            $lista = $consulta->fetchAll(PDO::FETCH_ASSOC);
            return $lista;
        }
    
        public static function obtenerVentasDeUnTipo($tipo){
            $lista = array();
            $objetoAccesoDato = DBController::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT * FROM ventaHamburguesas WHERE tipo = :tipo');
            $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
            $consulta->execute();
            $lista = $consulta->fetchAll(PDO::FETCH_ASSOC);
            return $lista;
        }
    
        public static function checkVentaId($numeroDePedido) {
            $objetoAccesoDato = DBController::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT * FROM ventaHamburguesas WHERE numeroDePedido = :numeroDePedido');
            $consulta->bindValue(':numeroDePedido', $numeroDePedido, PDO::PARAM_INT);
            $consulta->execute();
            $lista = $consulta->fetchAll(PDO::FETCH_ASSOC);
            return (count($lista) > 0) ? true : false;
        }
    
        public static function modificarVenta($NumeroDePedido,$Email,$Nombre,$Tipo,$Cantidad)
        {
            $objetoAccesoDato = DBController::dameUnObjetoAcceso();
            $consulta =$objetoAccesoDato->RetornarConsulta(
                "UPDATE ventaHamburguesas 
                SET email = :Email,
                Nombre = :Nombre,
                tipo  = :Tipo,
                cantidad = :Cantidad
                WHERE numeroDePedido = :NumeroDePedido");
    
            $consulta->bindValue(':Email',$Email, PDO::PARAM_STR);
            $consulta->bindValue(':Nombre',$Nombre, PDO::PARAM_STR);
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
            $consulta = $objetoAccesoDato->RetornarConsulta('DELETE FROM ventaHamburguesas WHERE numeroDePedido = :numeroDePedido');
            $consulta->bindValue(':numeroDePedido', $numeroDePedido, PDO::PARAM_INT);
            $consulta->execute();
            return ($consulta->rowCount() != 0) ? $venta : null;
        }
    
        public static function getVenta($numeroDePedido)
        {
            $objetoAccesoDato = DBController::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT * FROM ventaHamburguesas WHERE numeroDePedido = :numeroDePedido');
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