<?php
require_once './models/Usuario.php';
require_once './utils/enums.php';

use GuzzleHttp\Psr7\Response;

class UsuarioMiddleware
{
    public function VerificarSocio($request, $handler)
    {
        $parametros = $request->getParsedBody();
        $usuario = Usuario::ObtenerPorUsuario($parametros['usuarioMW']);
        if ($usuario->id_tipo == TipoEmpleado::SOCIO->value) {
            $response = $handler->handle($request); 
            $newResponse = $response->withStatus(200);
        } else {
            $response = new Response();  
            $payload = json_encode(array("mensaje" => 'EL PERFIL DEL USUARIO NO ES SOCIO'));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(401);
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function VerificarMozo($request, $handler)
    {
        $parametros = $request->getParsedBody();
        $usuario = Usuario::ObtenerPorUsuario($parametros['usuarioMW']);
        if ($usuario->id_tipo == TipoEmpleado::MOZO->value) {
            $response = $handler->handle($request); 
            $newResponse = $response->withStatus(200);
        } else {
            $response = new Response();  
            $payload = json_encode(array("mensaje" => 'EL PERFIL DEL USUARIO NO ES MOZO'));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(401);
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function VerificarBartender($request, $handler)
    {
        $parametros = $request->getParsedBody();
        $usuario = Usuario::ObtenerPorUsuario($parametros['usuarioMW']);
        if ($usuario->id_tipo == TipoEmpleado::BARTENDER->value) {
            $response = $handler->handle($request); 
            $newResponse = $response->withStatus(200);
        } else {
            $response = new Response();  
            $payload = json_encode(array("mensaje" => 'EL PERFIL DEL USUARIO NO ES BARTENDER'));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(401);
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function VerificarCervecero($request, $handler)
    {
        $parametros = $request->getParsedBody();
        $usuario = Usuario::ObtenerPorUsuario($parametros['usuarioMW']);
        if ($usuario->id_tipo == TipoEmpleado::CERVECERO->value) {
            $response = $handler->handle($request); 
            $newResponse = $response->withStatus(200);
        } else {
            $response = new Response();  
            $payload = json_encode(array("mensaje" => 'EL PERFIL DEL USUARIO NO ES CERVECERO'));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(401);
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function VerificarCocinero($request, $handler)
    {
        $parametros = $request->getParsedBody();
        $usuario = Usuario::ObtenerPorUsuario($parametros['usuarioMW']);
        if ($usuario->id_tipo == TipoEmpleado::COCINERO->value) {
            $response = $handler->handle($request); 
            $newResponse = $response->withStatus(200);
        } else {
            $response = new Response();  
            $payload = json_encode(array("mensaje" => 'EL PERFIL DEL USUARIO NO ES COCINERO'));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(401);
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

} 
