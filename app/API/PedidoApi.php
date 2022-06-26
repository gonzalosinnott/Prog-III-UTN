<?php

require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidoApi extends Pedido
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id_mesa = $parametros['id_mesa'];
        $cliente = $parametros['cliente'];
        $tiempo_estimado = $parametros['tiempo_estimado'];

        $pedido = new Pedido();
        $pedido->id_mesa = $id_mesa;
        $pedido->cliente = $cliente;
        $pedido->tiempo_estimado = $tiempo_estimado;

        $retorno = Mesa::ObtenerEstadoMesaLibre($id_mesa);

        if ($retorno != null) {
            $retorno = Pedido::Alta($pedido);

            if ($retorno == true) {
                $payload = json_encode(array("mensaje" => "Pedido creado con exito"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(201);
            } else {
                $payload = json_encode(array("mensaje" => "ERROR AL CARGAR EL PEDIDO"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(401);
            }
        } else {
            $payload = json_encode(array("mensaje" => "LA MESA ELEGIDA ESTA OCUPADA O NO EXISTE"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(400);
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

}
