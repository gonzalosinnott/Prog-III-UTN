<?php


class Producto
{
    public $id_producto;
    public $nombre;
    public $precio;
    public $id_sector;
    public $tiempo_preparacion;
    public $estado;

    public function CrearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta('INSERT INTO producto (id_producto, nombre, precio, id_sector, tiempo_preparacion, estado) VALUES(:id_producto, :nombre, :precio, :id_sector, :tiempo_preparacion, :estado)');
        $consulta->bindValue(':id_producto', $this->id_producto, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':id_sector', $this->id_sector, PDO::PARAM_INT);
        $consulta->bindValue(':tiempo_preparacion', $this->tiempo_preparacion, PDO::PARAM_INT);
        $consulta->bindValue(':estado', 1, PDO::PARAM_INT);
        $consulta->execute();
        return true;
    }

    public function ModificarProducto($producto)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta('UPDATE producto SET nombre = :nombre, precio = :precio, id_sector = :id_sector, tiempo_preparacion = :tiempo_preparacion WHERE id_producto = :id_producto');
        $consulta->bindValue(':id_producto', $producto->id_producto, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $producto->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $producto->precio, PDO::PARAM_INT);
        $consulta->bindValue(':id_sector', $producto->id_sector, PDO::PARAM_INT);
        $consulta->bindValue(':tiempo_preparacion', $producto->tiempo_preparacion, PDO::PARAM_INT);
        $consulta->execute();
    }

    public function MostrarProductos()
    {
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta('SELECT * FROM producto WHERE estado = 1');
            $consulta->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $consulta->fetchAll(PDO::FETCH_CLASS, "Producto");
    }

    public static function ObtenerPorSector($sector)
    {
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM producto WHERE id_sector = :id_sector AND estado = 1");
            $consulta->bindValue(':id_sector', $sector, PDO::PARAM_STR);
            $consulta->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $consulta->fetchAll(PDO::FETCH_CLASS, "Producto");
    }

    public function CambiarEstadoProducto($id_producto, $estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta('UPDATE producto SET estado = :estado WHERE id_producto = :id_producto');
        $consulta->bindValue(':id_producto', $id_producto, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $estado, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function ObtenerPorId($id_producto)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM producto WHERE id_producto = :id_producto");
        $consulta->bindValue(':id_producto', $id_producto, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('producto');
    }

    public static function ObtenerTiempoPreparacion($id_producto)
    {
        $producto =  Self::ObtenerPorId($id_producto);
        return $producto->tiempo_preparacion;
    }
   

    ///REVISAR
    public static function ObtenerProductosPorEmpleadoSectorPedidoPendiente($id_sector, $estado_pedido)
    {
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT PE.id_producto, PR.nombre, PR.id_sector from producto PR INNER JOIN pedido PE ON PR.id_producto = PE.id_producto WHERE PE.estado_pedido = :estado_pedido AND PR.id_sector = :id_sector");
            $consulta->bindValue(':estado_pedido', $estado_pedido, PDO::PARAM_STR);
            $consulta->bindValue(':id_sector', $id_sector, PDO::PARAM_STR);
            $consulta->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $consulta->fetchAll(PDO::FETCH_CLASS, "Producto");
    }    
}
