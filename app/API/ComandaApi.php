<?php

require_once './models/Comanda.php';
require_once './models/Pedido.php';
require_once './models/Producto.php';
require_once './models/Mesa.php';
require_once './utils/enums.php';

class ComandaApi extends Comanda implements IApiUsable
{
    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();

        $codigo_pedido = $parametros['codigo_pedido'];
        $id_producto = $parametros['id_producto'];
        $cantidad = $parametros['cantidad'];

        $comanda = new Comanda();
        $comanda->codigo_pedido = $codigo_pedido;
        $comanda->id_producto = $id_producto;
        $comanda->cantidad = $cantidad;

        self::CheckPedido($comanda, $response); 
        
        return $response
               ->withHeader('Content-Type', 'application/json');
    }

    private function CheckPedido ($comanda, $response) {

        $retorno = Pedido::ObtenerPorCodigo($comanda->codigo_pedido);

        if ($retorno != NULL) {
            self::CheckProducto($comanda, $response);
            
        } else {
            $payload = json_encode(array("mensaje" => "EL PEDIDO SELECCIONADO NO EXISTE"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value);  
            return $newResponse
               ->withHeader('Content-Type', 'application/json');         
        }    
            
    }

    private function CheckProducto ($comanda, $response) {

        $retorno = Producto::ObtenerPorId($comanda->id_producto);

        if ($retorno != null) {

            self::AltaComanda($comanda, $response);
            
        } else {
            $payload = json_encode(array("mensaje" => "EL PRODUCTO ELEGIDO NO EXISTE"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value); 
            return $newResponse
               ->withHeader('Content-Type', 'application/json');          
        }          
    }

    private function AltaComanda($comanda, $response) {
        
        $retorno = Comanda::Alta($comanda);

        if ($retorno == true) {    

            $producto = Producto::ObtenerPorId($comanda->id_producto);
            $precioTotal = $producto->precio * $comanda->cantidad;
            
            Pedido::ActualizarPrecioPedido($comanda->codigo_pedido, $precioTotal);
            Pedido::ActualizarTiempoEspera($comanda->codigo_pedido, $producto->tiempo_preparacion);

            $payload = json_encode(array("mensaje" => "Comanda creado con exito"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::CREATED->value);
        } else {
            $payload = json_encode(array("mensaje" => "ERROR AL CARGAR LA COMANDA"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::BAD_REQUEST->value);
        }

        return $newResponse
               ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $comandas = Comanda::MostrarComandas();
        if(count($comandas) > 0)
        {            
            $payload = json_encode(array("Comandas: " => $comandas));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::OK->value);
        }
        else
        {
            $payload = json_encode(array("mensaje" => "NO EXISTEN COMANDAS"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value); 
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodosPorEstado($request, $response, $args)
    {
        $sector = $args['estado'];
        $lista = Comanda::ObtenerPorEstado($sector);
        if(count($lista) > 0)
        {            
            $payload = json_encode(array("Comandas: " => $lista));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::OK->value);
        }
        else
        {
            $payload = json_encode(array("mensaje" => "NO EXISTEN COMANDAS CON EL ESTADO INDICADO"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value); 
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['identificador'];
        $usuario = Comanda::ObtenerPorId($id);
        
        if($usuario != null)
        {
            $payload = json_encode(array("Comanda: " => $usuario));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::OK->value);
        }
        else
        {
            $payload = json_encode(array("mensaje" => "COMANDA NO ENCONTRADA"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value);
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id = $args['identificador'];
        $codigo_pedido = $parametros['codigo_pedido'];
        $id_producto = $parametros['id_producto'];
        $cantidad = $parametros['cantidad'];
        
        $comanda = Comanda::ObtenerPorId($id);        

        if ($comanda != null) {

            $checkPedido = Pedido::ObtenerPorCodigo($codigo_pedido);

            if ($checkPedido == NULL) {
                
                $payload = json_encode(array("mensaje" => "EL PEDIDO ELEGIDO NO EXISTE"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value);  
                return $newResponse
                ->withHeader('Content-Type', 'application/json');         
            } 
            
            $checkProducto = Producto::ObtenerPorId($id_producto);

            if ($checkProducto == null) {

                $payload = json_encode(array("mensaje" => "EL PRODUCTO ELEGIDO NO EXISTE"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value); 
                return $newResponse
                ->withHeader('Content-Type', 'application/json');          
            }  

            $comanda->codigo_pedido = $codigo_pedido;
            $comanda->id_producto = $id_producto;
            $comanda->cantidad = $cantidad;
            $modificar = Comanda::ModificarComanda($comanda);

            if($modificar == true)
            {
                ///VER COMO CAMBIAR EL PRECIO FINAL AL MODIFICAR UNA COMANDA
    
                $payload = json_encode(array("mensaje" => "Comanda modificada con exito"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::OK->value); 
            }
            else{
                $payload = json_encode(array("mensaje" => "ERROR AL MODIFICAR LA COMANDA"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::BAD_REQUEST->value);
            }                
        } else {
            $payload = json_encode(array("mensaje" => "COMANDA INEXISTENTE"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value);
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $comandaModificar = $args['identificador'];
        $comanda = Comanda::ObtenerPorId($comandaModificar);

        if ($comanda != null) {
            Comanda::CambiarActivoComanda($comanda, AltaBaja::BAJA->value);
            $payload = json_encode(array("mensaje" => "Comanda Borrado con exito"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::OK->value);
        } else {
            $payload = json_encode(array("mensaje" => "COMANDA INEXISTENTE"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value);
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPendientesBartender($request, $response, $args)
    {
        $lista = Comanda::ObtenerComandaSectorEstado(Sector::BARRA->value, EstadoComanda::PENDIENTE->value);
        
        if(count($lista) > 0)
        {            
            $payload = json_encode(array("Comandas: " => $lista));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::OK->value);
        }
        else
        {
            $payload = json_encode(array("mensaje" => "NO EXISTEN COMANDAS PENDIENTES PARA EL BARMAN"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value); 
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPendientesCervecero($request, $response, $args)
    {
        $lista = Comanda::ObtenerComandaSectorEstado(Sector::CHOPERA->value, EstadoComanda::PENDIENTE->value);
        
        if(count($lista) > 0)
        {            
            $payload = json_encode(array("Comandas: " => $lista));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::OK->value);
        }
        else
        {
            $payload = json_encode(array("mensaje" => "NO EXISTEN COMANDAS PENDIENTES PARA EL CERVECERO"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value); 
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPendientesCocina($request, $response, $args)
    {
        $lista = Comanda::ObtenerComandaSectorEstado(Sector::COCINA->value, EstadoComanda::PENDIENTE->value);
        
        if(count($lista) > 0)
        {            
            $payload = json_encode(array("Comandas: " => $lista));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::OK->value);
        }
        else
        {
            $payload = json_encode(array("mensaje" => "NO EXISTEN COMANDAS PENDIENTES PARA LA COCINA"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value); 
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPendientesCandybar($request, $response, $args)
    {
        $lista = Comanda::ObtenerComandaSectorEstado(Sector::CANDYBAR->value, EstadoComanda::PENDIENTE->value);
        
        if(count($lista) > 0)
        {            
            $payload = json_encode(array("Comandas: " => $lista));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::OK->value);
        }
        else
        {
            $payload = json_encode(array("mensaje" => "NO EXISTEN COMANDAS PENDIENTES PARA EL CANDYBAR"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value); 
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function PrepararPedidoBartender($request, $response, $args)
    {
        $id = $args['identificador'];
                
        $comanda = Comanda::ObtenerPorId($id);
        $producto = Producto::ObtenerPorId($comanda->id_producto);        

        if ($comanda != null) {

            if ($comanda->id_sector != Sector::BARRA->value) {
                
                $payload = json_encode(array("mensaje" => "EL PEDIDO NO PERTENECE A SU SECTOR"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value);  
                return $newResponse
                ->withHeader('Content-Type', 'application/json');         
            } 
            
            if ($comanda->estado != EstadoComanda::PENDIENTE->value) {

                $payload = json_encode(array("mensaje" => "EL PEDIDO NO ESTA PENDIENTE"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value); 
                return $newResponse
                ->withHeader('Content-Type', 'application/json');          
            }  

            $modificar = Comanda::CambiarEstadoComanda($comanda, EstadoComanda::EN_PREPARACION->value);

            if($modificar)
            {
                if(self::CheckEstadoPedido($comanda->codigo_pedido, EstadoComanda::EN_PREPARACION->value))
                {
                    $payload = json_encode(array("mensaje" => "Pedido en Preparacion - Tiempo estimado: " . $producto->tiempo_preparacion . " minutos", "ACTUALIZACION:" => "TODOS LOS PEDIDOS EN PREPARACION"));
                    $response->getBody()->write($payload);
                    $newResponse = $response->withStatus(HttpCode::OK->value); 
                }
                else 
                {
                $payload = json_encode(array("mensaje" => "Pedido en Preparacion - Tiempo estimado: " . $producto->tiempo_preparacion . " minutos", "ACTUALIZACION:" => "QUEDAN PEDIDOS POR ENVIAR A PREPARACION"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::OK->value); 
                }
            }
            else{
                $payload = json_encode(array("mensaje" => "ERROR AL MODIFICAR LA COMANDA"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::BAD_REQUEST->value);
            }                
        } else {
            $payload = json_encode(array("mensaje" => "COMANDA INEXISTENTE"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value);
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function PrepararPedidoCervecero($request, $response, $args)
    {
        $id = $args['identificador'];
                
        $comanda = Comanda::ObtenerPorId($id);
        $producto = Producto::ObtenerPorId($comanda->id_producto);        

        if ($comanda != null) {

            if ($comanda->id_sector != Sector::CHOPERA->value) {
                
                $payload = json_encode(array("mensaje" => "EL PEDIDO NO PERTENECE A SU SECTOR"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value);  
                return $newResponse
                ->withHeader('Content-Type', 'application/json');         
            } 
            
            if ($comanda->estado != EstadoComanda::PENDIENTE->value) {

                $payload = json_encode(array("mensaje" => "EL PEDIDO NO ESTA PENDIENTE"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value); 
                return $newResponse
                ->withHeader('Content-Type', 'application/json');          
            }  

            $modificar = Comanda::CambiarEstadoComanda($comanda, EstadoComanda::EN_PREPARACION->value);

            if($modificar)
            {
                if(self::CheckEstadoPedido($comanda->codigo_pedido, EstadoComanda::EN_PREPARACION->value))
                {
                    $payload = json_encode(array("mensaje" => "Pedido en Preparacion - Tiempo estimado: " . $producto->tiempo_preparacion . " minutos", "ACTUALIZACION:" => "TODOS LOS PEDIDOS EN PREPARACION"));
                    $response->getBody()->write($payload);
                    $newResponse = $response->withStatus(HttpCode::OK->value); 
                }
                else 
                {
                $payload = json_encode(array("mensaje" => "Pedido en Preparacion - Tiempo estimado: " . $producto->tiempo_preparacion . " minutos", "ACTUALIZACION:" => "QUEDAN PEDIDOS POR ENVIAR A PREPARACION"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::OK->value); 
                }
            }
            else{
                $payload = json_encode(array("mensaje" => "ERROR AL MODIFICAR LA COMANDA"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::BAD_REQUEST->value);
            }                
        } else {
            $payload = json_encode(array("mensaje" => "COMANDA INEXISTENTE"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value);
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function PrepararPedidoCocina($request, $response, $args)
    {
        $id = $args['identificador'];
                
        $comanda = Comanda::ObtenerPorId($id);
        $producto = Producto::ObtenerPorId($comanda->id_producto);        

        if ($comanda != null) {

            if ($comanda->id_sector != Sector::COCINA->value) {
                
                $payload = json_encode(array("mensaje" => "EL PEDIDO NO PERTENECE A SU SECTOR"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value);  
                return $newResponse
                ->withHeader('Content-Type', 'application/json');         
            } 
            
            if ($comanda->estado != EstadoComanda::PENDIENTE->value) {

                $payload = json_encode(array("mensaje" => "EL PEDIDO NO ESTA PENDIENTE"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value); 
                return $newResponse
                ->withHeader('Content-Type', 'application/json');          
            }  

            $modificar = Comanda::CambiarEstadoComanda($comanda, EstadoComanda::EN_PREPARACION->value);

            if($modificar)
            {
                if(self::CheckEstadoPedido($comanda->codigo_pedido, EstadoComanda::EN_PREPARACION->value))
                {
                    $payload = json_encode(array("mensaje" => "Pedido en Preparacion - Tiempo estimado: " . $producto->tiempo_preparacion . " minutos", "ACTUALIZACION:" => "TODOS LOS PEDIDOS EN PREPARACION"));
                    $response->getBody()->write($payload);
                    $newResponse = $response->withStatus(HttpCode::OK->value); 
                }
                else 
                {
                $payload = json_encode(array("mensaje" => "Pedido en Preparacion - Tiempo estimado: " . $producto->tiempo_preparacion . " minutos", "ACTUALIZACION:" => "QUEDAN PEDIDOS POR ENVIAR A PREPARACION"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::OK->value); 
                }
            }
            else{
                $payload = json_encode(array("mensaje" => "ERROR AL MODIFICAR LA COMANDA"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::BAD_REQUEST->value);
            }                
        } else {
            $payload = json_encode(array("mensaje" => "COMANDA INEXISTENTE"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value);
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function PrepararPedidoCandybar($request, $response, $args)
    {
        $id = $args['identificador'];
                
        $comanda = Comanda::ObtenerPorId($id);
        $producto = Producto::ObtenerPorId($comanda->id_producto);        

        if ($comanda != null) {

            if ($comanda->id_sector != Sector::CANDYBAR->value) {
                
                $payload = json_encode(array("mensaje" => "EL PEDIDO NO PERTENECE A SU SECTOR"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value);  
                return $newResponse
                ->withHeader('Content-Type', 'application/json');         
            } 
            
            if ($comanda->estado != EstadoComanda::PENDIENTE->value) {

                $payload = json_encode(array("mensaje" => "EL PEDIDO NO ESTA PENDIENTE"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value); 
                return $newResponse
                ->withHeader('Content-Type', 'application/json');          
            }  

            $modificar = Comanda::CambiarEstadoComanda($comanda, EstadoComanda::EN_PREPARACION->value);

            if($modificar)
            {
                if(self::CheckEstadoPedido($comanda->codigo_pedido, EstadoComanda::EN_PREPARACION->value))
                {
                    $payload = json_encode(array("mensaje" => "Pedido en Preparacion - Tiempo estimado: " . $producto->tiempo_preparacion . " minutos", "ACTUALIZACION:" => "TODOS LOS PEDIDOS EN PREPARACION"));
                    $response->getBody()->write($payload);
                    $newResponse = $response->withStatus(HttpCode::OK->value); 
                }
                else 
                {
                $payload = json_encode(array("mensaje" => "Pedido en Preparacion - Tiempo estimado: " . $producto->tiempo_preparacion . " minutos", "ACTUALIZACION:" => "QUEDAN PEDIDOS POR ENVIAR A PREPARACION"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::OK->value); 
                }
            }
            else{
                $payload = json_encode(array("mensaje" => "ERROR AL MODIFICAR LA COMANDA"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::BAD_REQUEST->value);
            }                
        } else {
            $payload = json_encode(array("mensaje" => "COMANDA INEXISTENTE"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value);
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerEnPreparacionBartender($request, $response, $args)
    {
        $lista = Comanda::ObtenerComandaSectorEstado(Sector::BARRA->value, EstadoComanda::EN_PREPARACION->value);
        
        if(count($lista) > 0)
        {            
            $payload = json_encode(array("Comandas: " => $lista));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::OK->value);
        }
        else
        {
            $payload = json_encode(array("mensaje" => "NO EXISTEN COMANDAS EN PREPARACION PARA EL BARMAN"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value); 
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerEnPreparacionCervecero($request, $response, $args)
    {
        $lista = Comanda::ObtenerComandaSectorEstado(Sector::CHOPERA->value, EstadoComanda::EN_PREPARACION->value);
        
        if(count($lista) > 0)
        {            
            $payload = json_encode(array("Comandas: " => $lista));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::OK->value);
        }
        else
        {
            $payload = json_encode(array("mensaje" => "NO EXISTEN COMANDAS EN PREPARACION PARA EL CERVECERO"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value); 
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerEnPreparacionCocina($request, $response, $args)
    {
        $lista = Comanda::ObtenerComandaSectorEstado(Sector::COCINA->value, EstadoComanda::EN_PREPARACION->value);
        
        if(count($lista) > 0)
        {            
            $payload = json_encode(array("Comandas: " => $lista));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::OK->value);
        }
        else
        {
            $payload = json_encode(array("mensaje" => "NO EXISTEN COMANDAS EN PREPARACION PARA LA COCINA"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value); 
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerEnPreparacionCandybar($request, $response, $args)
    {
        $lista = Comanda::ObtenerComandaSectorEstado(Sector::CANDYBAR->value, EstadoComanda::EN_PREPARACION->value);
        
        if(count($lista) > 0)
        {            
            $payload = json_encode(array("Comandas: " => $lista));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::OK->value);
        }
        else
        {
            $payload = json_encode(array("mensaje" => "NO EXISTEN COMANDAS EN PREPARACION PARA EL CANDYBAR"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value); 
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function EntregarPedidoBartender($request, $response, $args)
    {
        $id = $args['identificador'];
                
        $comanda = Comanda::ObtenerPorId($id);
        $producto = Producto::ObtenerPorId($comanda->id_producto);        

        if ($comanda != null) {

            if ($comanda->id_sector != Sector::BARRA->value) {
                
                $payload = json_encode(array("mensaje" => "EL PEDIDO NO PERTENECE A SU SECTOR"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value);  
                return $newResponse
                ->withHeader('Content-Type', 'application/json');         
            } 
            
            if ($comanda->estado != EstadoComanda::EN_PREPARACION->value) {

                $payload = json_encode(array("mensaje" => "EL PEDIDO NO ESTA EN PREPARACION"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value); 
                return $newResponse
                ->withHeader('Content-Type', 'application/json');          
            }  

            $modificar = Comanda::CambiarEstadoComanda($comanda, EstadoComanda::LISTO->value);

            if($modificar)
            {
                if(self::CheckEstadoPedido($comanda->codigo_pedido, EstadoComanda::LISTO->value))
                {
                    $payload = json_encode(array("mensaje" => "Pedido listo para entregar", "ACTUALIZACION:" => "TODOS LOS PEDIDOS LISTOS PARA ENTREGAR"));
                    $response->getBody()->write($payload);
                    $newResponse = $response->withStatus(HttpCode::OK->value); 
                }
                else 
                {
                $payload = json_encode(array("mensaje" => "Pedido listo para entregar", "ACTUALIZACION:" => "QUEDAN PEDIDOS POR PREPARAR"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::OK->value); 
                }
            }
            else{
                $payload = json_encode(array("mensaje" => "ERROR AL MODIFICAR LA COMANDA"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::BAD_REQUEST->value);
            }                
        } else {
            $payload = json_encode(array("mensaje" => "COMANDA INEXISTENTE"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value);
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function EntregarPedidoCervecero($request, $response, $args)
    {
        $id = $args['identificador'];
                
        $comanda = Comanda::ObtenerPorId($id);
        $producto = Producto::ObtenerPorId($comanda->id_producto);        

        if ($comanda != null) {

            if ($comanda->id_sector != Sector::CHOPERA->value) {
                
                $payload = json_encode(array("mensaje" => "EL PEDIDO NO PERTENECE A SU SECTOR"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value);  
                return $newResponse
                ->withHeader('Content-Type', 'application/json');         
            } 
            
            if ($comanda->estado != EstadoComanda::EN_PREPARACION->value) {

                $payload = json_encode(array("mensaje" => "EL PEDIDO NO ESTA EN PREPARACION"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value); 
                return $newResponse
                ->withHeader('Content-Type', 'application/json');          
            }  

            $modificar = Comanda::CambiarEstadoComanda($comanda, EstadoComanda::LISTO->value);

            if($modificar)
            {
                if(self::CheckEstadoPedido($comanda->codigo_pedido, EstadoComanda::LISTO->value))
                {
                    $payload = json_encode(array("mensaje" => "Pedido listo para entregar", "ACTUALIZACION:" => "TODOS LOS PEDIDOS LISTOS PARA ENTREGAR"));
                    $response->getBody()->write($payload);
                    $newResponse = $response->withStatus(HttpCode::OK->value); 
                }
                else 
                {
                $payload = json_encode(array("mensaje" => "Pedido listo para entregar", "ACTUALIZACION:" => "QUEDAN PEDIDOS POR PREPARAR"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::OK->value); 
                }
            }
            else{
                $payload = json_encode(array("mensaje" => "ERROR AL MODIFICAR LA COMANDA"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::BAD_REQUEST->value);
            }                
        } else {
            $payload = json_encode(array("mensaje" => "COMANDA INEXISTENTE"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value);
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function EntregarPedidoCocina($request, $response, $args)
    {
        $id = $args['identificador'];
                
        $comanda = Comanda::ObtenerPorId($id);
        $producto = Producto::ObtenerPorId($comanda->id_producto);        

        if ($comanda != null) {

            if ($comanda->id_sector != Sector::COCINA->value) {
                
                $payload = json_encode(array("mensaje" => "EL PEDIDO NO PERTENECE A SU SECTOR"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value);  
                return $newResponse
                ->withHeader('Content-Type', 'application/json');         
            } 
            
            if ($comanda->estado != EstadoComanda::EN_PREPARACION->value) {

                $payload = json_encode(array("mensaje" => "EL PEDIDO NO ESTA EN PREPARACION"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value); 
                return $newResponse
                ->withHeader('Content-Type', 'application/json');          
            }  

            $modificar = Comanda::CambiarEstadoComanda($comanda, EstadoComanda::LISTO->value);

            if($modificar)
            {
                if(self::CheckEstadoPedido($comanda->codigo_pedido, EstadoComanda::LISTO->value))
                {
                    $payload = json_encode(array("mensaje" => "Pedido listo para entregar", "ACTUALIZACION:" => "TODOS LOS PEDIDOS LISTOS PARA ENTREGAR"));
                    $response->getBody()->write($payload);
                    $newResponse = $response->withStatus(HttpCode::OK->value); 
                }
                else 
                {
                $payload = json_encode(array("mensaje" => "Pedido listo para entregar", "ACTUALIZACION:" => "QUEDAN PEDIDOS POR PREPARAR"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::OK->value); 
                }
            }
            else{
                $payload = json_encode(array("mensaje" => "ERROR AL MODIFICAR LA COMANDA"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::BAD_REQUEST->value);
            }                
        } else {
            $payload = json_encode(array("mensaje" => "COMANDA INEXISTENTE"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value);
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function EntregarPedidoCandybar($request, $response, $args)
    {
        $id = $args['identificador'];
                
        $comanda = Comanda::ObtenerPorId($id);
        $producto = Producto::ObtenerPorId($comanda->id_producto);        

        if ($comanda != null) {

            if ($comanda->id_sector != Sector::CANDYBAR->value) {
                
                $payload = json_encode(array("mensaje" => "EL PEDIDO NO PERTENECE A SU SECTOR"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value);  
                return $newResponse
                ->withHeader('Content-Type', 'application/json');         
            } 
            
            if ($comanda->estado != EstadoComanda::EN_PREPARACION->value) {

                $payload = json_encode(array("mensaje" => "EL PEDIDO NO ESTA EN PREPARACION"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value); 
                return $newResponse
                ->withHeader('Content-Type', 'application/json');          
            }  

            $modificar = Comanda::CambiarEstadoComanda($comanda, EstadoComanda::LISTO->value);

            if($modificar)
            {
                if(self::CheckEstadoPedido($comanda->codigo_pedido, EstadoComanda::LISTO->value))
                {
                    $payload = json_encode(array("mensaje" => "Pedido listo para entregar", "ACTUALIZACION:" => "TODOS LOS PEDIDOS LISTOS PARA ENTREGAR"));
                    $response->getBody()->write($payload);
                    $newResponse = $response->withStatus(HttpCode::OK->value); 
                }
                else 
                {
                $payload = json_encode(array("mensaje" => "Pedido listo para entregar", "ACTUALIZACION:" => "QUEDAN PEDIDOS POR PREPARAR"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::OK->value); 
                }
            }
            else{
                $payload = json_encode(array("mensaje" => "ERROR AL MODIFICAR LA COMANDA"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::BAD_REQUEST->value);
            }                
        } else {
            $payload = json_encode(array("mensaje" => "COMANDA INEXISTENTE"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value);
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }


    private static function CheckEstadoPedido($id, $estado)
    {
        $comandas = Comanda::ObtenerPorIdPedidoEstado($id, $estado);

        if(count($comandas) == 0)
        {
            $pedido = Pedido::ObtenerPorCodigo($id);
            Pedido::CambiarEstadoPedido($pedido, $estado);
            return true;
        }

        return false;
        
    }
}
