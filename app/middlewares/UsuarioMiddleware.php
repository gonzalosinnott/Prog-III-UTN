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
            $payload = json_encode(array("mensaje" => "Usuario con perfil socio"));
            $response->getBody()->write($payload);
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
            $payload = json_encode(array("mensaje" => "Usuario con perfil Mozo"));
            $response->getBody()->write($payload);
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
} 
