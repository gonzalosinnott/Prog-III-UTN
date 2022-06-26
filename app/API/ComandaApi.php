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

        $id_pedido = $parametros['id_pedido'];
        $id_producto = $parametros['id_producto'];
        $cantidad = $parametros['cantidad'];

        $comanda = new Comanda();
        $comanda->id_pedido = $id_pedido;
        $comanda->id_producto = $id_producto;
        $comanda->cantidad = $cantidad;

        self::CheckPedido($comanda, $response); 
        
        return $response
               ->withHeader('Content-Type', 'application/json');
    }

    private function CheckPedido ($comanda, $response) {

        $retorno = Pedido::ObtenerPorId($comanda->id_pedido);

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

            $precioUnitario = Producto::ObtenerPrecio($comanda->id_producto);
            $precioTotal = $precioUnitario * $comanda->cantidad;
            
            Pedido::ActualizarPrecioPedido($comanda->id_pedido, $precioTotal);

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
        $id_pedido = $parametros['id_pedido'];
        $id_producto = $parametros['id_producto'];
        $cantidad = $parametros['cantidad'];
        
        $comanda = Comanda::ObtenerPorId($id);        

        if ($comanda != null) {

            $checkPedido = Pedido::ObtenerPorId($id_pedido);

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

            $comanda->id_pedido = $id_pedido;
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
            Comanda::CambiarEstadoComanda($comanda, AltaBaja::BAJA->value);
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
}
