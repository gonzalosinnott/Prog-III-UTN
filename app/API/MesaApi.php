<?php

require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaApi extends Mesa implements IApiUsable
{
    public function TraerTodos($request, $response, $args)
    {
        $mesas = Mesa::MostrarMesas();
        $payload = json_encode(array("Lista de mesas: " => $mesas));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodosPor_Estado($request, $response, $args)
    {
        echo $args['estado'] . "<br>";
        $lista = Mesa::ObtenerPorEstado($args['estado']);
        $payload = json_encode(array("Lista de mesas: " => $lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ListarMesasConEstado($request, $response, $args)
    {
        $mesas = Mesa::MostrarMesasPorEstado();
        if (count($mesas)) {
            $payload = json_encode(array("Lista de mesas: " => $mesas));
        } else {
            $payload = json_encode(array("Lista de mesas: " => "No hay mesas"));
        }
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    #region ABM
    public function CargarUno($request, $response, $args)
    {
        $ArrayDeParametros = $request->getParsedBody();
        $mesa = new Mesa();
        $mesa->id_mesa = $ArrayDeParametros['id_mesa'];
        $mesa->estado_mesa = $ArrayDeParametros['estado_mesa'];

        if ($mesa->CrearMesa() != 0) {
            $payload = json_encode(array("mensaje" => "Mesa creado con exito"));
        } else {
            $payload = json_encode(array("mensaje" => "Error al crear el Mesa"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $mesaModificar = $args['id_mesa'];
        $mesa = Mesa::ObtenerPorId($mesaModificar);

        if ($mesa != null) {
            Mesa::CambiarEstadoMesa($mesa, 6);
            $payload = json_encode(array("mensaje" => "Mesa borrado con exito"));
        } else {
            $payload = json_encode(array("mensaje" => "Error al borrar el Mesa"));
        }
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $mesaModificar = $args['id_mesa'];
        $estado_mesa = $parametros['estado_mesa'];
        $mesa = Mesa::ObtenerPorId($mesaModificar);
        if ($mesa != null) {
            $mesa->estado_mesa = $estado_mesa;
            Mesa::modificarMesa($mesa);
            $payload = json_encode(array("mensaje" => "Mesa modificado con exito"));
        } else {
            $payload = json_encode(array("mensaje" => "Error al modificar el Mesa"));
        }
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // $id = $args['id'];
        // //   $mesa = Mesa::TraerUnaMesa($id);
        // $newResponse = $response->withJson($mesa, 200);
        // return $newResponse;
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
