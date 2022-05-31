<?php

use GuzzleHttp\Psr7\Response;

class Logger
{
    ///Ejercicio 01 CLASE 10
    public static function VerificadorCredenciales($request, $handler)
    {
        $method = $request->getMethod();   
        $response = new Response();

        if($method=="GET")
        {           
            $response=$handler->handle($request);
            $response->getBody()->write("El METODO DE LA SOLICITUD ES ".$method);
        }
        else if($method=="POST")
        {   
            $data = $request->getParsedBody();
            $nombre = $data['nombre'];
            $perfil = $data['perfil'];
            
            if($perfil == 'administrador')
            {
                $response=$handler->handle($request);
                $response->getBody()->write("\n El METODO DE LA SOLICITUD ES ".$method);
                $response->getBody()->write("\n Bienvenido ".$nombre);
            }
            else
            {
                $response->getBody()->write("\n USUARIO NO AUTORIZADO");
            }
        }

        return $response;
    }

    ///Ejercicio 02 CLASE 10
    public static function VerificadorCredenciales2($request, $handler)
    {
        $method = $request->getMethod();   
        $response = new Response();
                  

        if($method=="GET")
        {
            $response->getBody()->write(json_encode(["API" => $method]));
        }
        else if($method=="POST")
        {   
            $data = $request->getParsedBody();
            $nombre = $data['nombre'];
            $perfil = $data['perfil'];
            
            if($perfil == 'administrador')
            {
                $response=$handler->handle($request);
                $response->getBody()->write(json_encode(["API" => $method]));
                $response->getBody()->write(json_encode(["nombre" => $nombre, "perfil" => $perfil]));
            }
            else
            {
                $response->getBody()->write(json_encode(["error" => "no tiene permisos"]));
                $response = $response->withStatus(403);
            }
        }
        return $response;
    }
}

?>