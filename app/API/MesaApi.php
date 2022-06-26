<?php

require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaApi extends Mesa implements IApiUsable
{
    public function TraerTodos($request, $response, $args)
    {
        $mesas = Mesa::MostrarMesas();
        if(count($mesas) > 0)
        {            
            $payload = json_encode(array("Lista: " => $mesas));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(200);
        }
        else
        {
            $payload = json_encode(array("mensaje" => "NO EXISTEN MESAS"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(404); 
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodosPorEstado($request, $response, $args)
    {
        $sector = $args['estado'];
        $lista = Mesa::ObtenerPorEstado($args['estado']);
        if(count($lista) > 0)
        {            
            $payload = json_encode(array("Lista: " => $lista));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(200);

        }
        else
        {
            $payload = json_encode(array("mensaje" => "NO EXISTEN MESAS DEL ESATADO INDICADO"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(404); 
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function CargarUno($request, $response, $args)
    {
        $mesa = new Mesa();
        $mesa->estado_mesa = 5;

        $retorno = $mesa->CrearMesa();

        if ($retorno == true) {
            $payload = json_encode(array("mensaje" => "Mesa creada con exito"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(201);  

        } else {
            $payload = json_encode(array("mensaje" => "ERROR AL CREAR LA MESA"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(400);
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $mesaModificar = $args['identificador'];
        $mesa = Mesa::ObtenerPorId($mesaModificar);

        if ($mesa != null) {
            Mesa::CambiarEstadoMesa($mesa, 6);
            $payload = json_encode(array("mensaje" => "Mesa borrada con exito"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(200);
        } else {
            $payload = json_encode(array("mensaje" => "MESA INEXISTENTE"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(404);
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $mesaModificar = $args['identificador'];
        $estado_mesa = $parametros['estado_mesa'];
        $mesa = Mesa::ObtenerPorId($mesaModificar);
        if ($mesa != null) {
            $mesa->estado_mesa = $estado_mesa;
            Mesa::modificarMesa($mesa);
            $payload = json_encode(array("mensaje" => "Mesa modificada con exito"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(200);
        } else {
            $payload = json_encode(array("mensaje" => "MESA INEXISTENTE"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(404);
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['identificador'];
        $mesa = Mesa::ObtenerPorId($id);
        
        if($mesa != null)
        {
            $payload = json_encode(array("Mesa: " => $mesa));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(200);
        }
        else
        {
            $payload = json_encode(array("mensaje" => "MESA NO ENCONTRADA"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(404);
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    /*
    public function TraerMesa_MasUsada($request, $response, $args)
    {
        Mesa::getMesa_MasUsada();
        $payload = json_encode(array("mensaje" => "Exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function Traer_Mas_Menos_Usada($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $consulta = $parametros['consulta'];
        $fecha1 = $parametros['fecha1'];
        $fecha2 = $parametros['fecha2'];
        Mesa::getMesa_MasMenosUsada_Fecha($consulta, $fecha1, $fecha2);
        $payload = json_encode(array("mensaje" => "Exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    */
}
