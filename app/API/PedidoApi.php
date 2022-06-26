<?php

require_once './models/Pedido.php';
require_once './models/Usuario.php';
require_once './models/Pedido.php';
require_once './utils/enums.php';

class PedidoApi extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();

        $id_mesa = $parametros['id_mesa'];
        $id_mozo = $parametros['id_mozo'];
        $cliente = $parametros['cliente'];
        $tiempo_estimado = $parametros['tiempo_estimado'];

        $pedido = new Pedido();
        $pedido->id_mesa = $id_mesa;
        $pedido->id_mozo = $id_mozo;
        $pedido->cliente = $cliente;
        $pedido->tiempo_estimado = $tiempo_estimado;

        self::CheckMesa($pedido, $response); 
        
        return $response
               ->withHeader('Content-Type', 'application/json');

    }

    private function CheckMesa ($pedido, $response) {

        $retorno = Mesa::ObtenerEstadoMesaLibre($pedido->id_mesa, EstadoMesa::LIBRE->value);

        if ($retorno != NULL) {
            self::CheckMozo($pedido, $response);
            
        } else {
            $payload = json_encode(array("mensaje" => "LA MESA ELEGIDA ESTA OCUPADA O NO EXISTE"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value);  
            return $newResponse
               ->withHeader('Content-Type', 'application/json');         
        }    
            
    }

    private function CheckMozo ($pedido, $response) {

        $retorno = Usuario::AsignarEmpleado($pedido->id_mozo, TipoEmpleado::MOZO->value);

        if ($retorno != null) {

            self::AltaPedido($pedido, $response);
            
        } else {
            $payload = json_encode(array("mensaje" => "EL MOZO ELEGIDO NO EXISTE"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value); 
            return $newResponse
               ->withHeader('Content-Type', 'application/json');          
        }          
    }

    private function AltaPedido($pedido, $response) {
        
        $retorno = Pedido::Alta($pedido);

        if ($retorno == true) {

            $mesa = new Mesa();
            $mesa->id_mesa = $pedido->id_mesa;
            $mesa->estado_mesa = EstadoMesa::OCUPADA->value;
            $mesa->ModificarMesa($mesa);
            
            $payload = json_encode(array("mensaje" => "Pedido creado con exito"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::CREATED->value);
        } else {
            $payload = json_encode(array("mensaje" => "ERROR AL CARGAR EL PEDIDO"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::BAD_REQUEST->value);
        }

        return $newResponse
               ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $pedidos = Pedido::MostrarPedidos();
        if(count($pedidos) > 0)
        {            
            $payload = json_encode(array("Pedidos: " => $pedidos));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::OK->value);
        }
        else
        {
            $payload = json_encode(array("mensaje" => "NO EXISTEN PEDIDOS"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value); 
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodosPorEstado($request, $response, $args)
    {
        $sector = $args['estado'];
        $lista = Pedido::ObtenerPorEstado($sector);
        if(count($lista) > 0)
        {            
            $payload = json_encode(array("Pedidos: " => $lista));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::OK->value);
        }
        else
        {
            $payload = json_encode(array("mensaje" => "NO EXISTEN PEDIDOS CON EL ESTADO INDICADO"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value); 
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['identificador'];
        $usuario = Pedido::ObtenerPorId($id);
        
        if($usuario != null)
        {
            $payload = json_encode(array("Pedido: " => $usuario));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::OK->value);
        }
        else
        {
            $payload = json_encode(array("mensaje" => "PEDIDO NO ENCONTRADO"));
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
        $id_mesa = $parametros['id_mesa'];
        $id_mozo = $parametros['id_mozo'];
        $cliente = $parametros['cliente'];
        $hora_entrega = $parametros['hora_entrega'];
        
        $pedido = Pedido::ObtenerPorId($id);        

        if ($pedido != null) {

            $checkMesa = Mesa::ObtenerEstadoMesaLibre($id_mesa, EstadoMesa::LIBRE->value);

            if ($checkMesa == NULL) {
                
                $payload = json_encode(array("mensaje" => "LA MESA ELEGIDA ESTA OCUPADA O NO EXISTE"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value);  
                return $newResponse
                ->withHeader('Content-Type', 'application/json');         
            } 
            
            $checkMozo = Usuario::AsignarEmpleado($id_mozo, TipoEmpleado::MOZO->value);

            if ($checkMozo == null) {

                $payload = json_encode(array("mensaje" => "EL MOZO ELEGIDO NO EXISTE"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value); 
                return $newResponse
                ->withHeader('Content-Type', 'application/json');          
            }  

            $pedido->id_mesa = $id_mesa;
            $pedido->id_mozo = $id_mozo;
            $pedido->cliente = $cliente;
            $pedido->hora_entrega = $hora_entrega;
            Pedido::ModificarPedido($pedido);
            $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::OK->value);
        } else {
            $payload = json_encode(array("mensaje" => "PEDIDO INEXISTENTE"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value);
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $pedidoModificar = $args['identificador'];
        $pedido = Pedido::ObtenerPorId($pedidoModificar);

        if ($pedido != null) {
            Pedido::CambiarEstadoPedido($pedido, AltaBaja::BAJA->value);
            $payload = json_encode(array("mensaje" => "Pedido Borrado con exito"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::OK->value);
        } else {
            $payload = json_encode(array("mensaje" => "PEDIDO INEXISTENTE"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(HttpCode::NOT_FOUND->value);
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }
}
