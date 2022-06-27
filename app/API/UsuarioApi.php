<?php

include_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioApi extends Usuario implements IApiUsable
{
    public function LoginUsuario($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $usuario = $parametros["usuario"];
        $clave = $parametros["clave"];
        $claveHash = Usuario::GetHash($usuario);
        
        if($claveHash == true && password_verify($clave, $claveHash["clave"]))
        {
            $retorno = Usuario::Login($usuario, $claveHash["clave"]); 
            
            if ($retorno != null) {

                $token = AutentificadorJWT::crearToken($usuario);
                Usuario::ActualizarFechaLogin($retorno["id_empleado"]);
                $payload = json_encode(array("Estado" => "OK", "Mensaje" => "Logueado exitosamente.", "Token" => $token, "nombre_empleado" => $retorno["nombre_empleado"]));

                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(200); 

            } else {
                $payload = json_encode(array("Mensaje: " => "USUARIO INCORRECTO"));
                $response->getBody()->write($payload);
                $newResponse = $response->withStatus(401);
            } 
        }
        else
        {
            $payload = json_encode(array("Mensaje: " => "USUARIO O CONTRASEÑA  INCORRECTO"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(401); 
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }   
    
    public function TraerTodos($request, $response, $args)
    {
        $usuarios = Usuario::obtenerTodos();

        if(count($usuarios) > 0)
        {            
            $payload = json_encode(array("Lista: " => $usuarios));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(200);
        }
        else
        {
            $payload = json_encode(array("mensaje" => "NO EXISTEN USUARIOS"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(404); 
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodosPorTipo($request, $response, $args)
    {
        $identificador = $args['identificador'];
        $usuarios = Usuario::ObtenerPorTipo($identificador);
        
        if(count($usuarios) > 0)
        {            
            $payload = json_encode(array("Lista: " => $usuarios));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(200);

        }
        else
        {
            $payload = json_encode(array("mensaje" => "NO EXISTEN USUARIOS DEL TIPO INDICADO"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(404); 
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];
        $id_tipo = $parametros['id_tipo'];
        $nombre_usuario = $parametros['nombre_usuario'];
        $estado = $parametros['estado'];

        $objeto = new Usuario();

        $objeto->usuario = $usuario;
        $objeto->clave = $clave;
        $objeto->id_tipo = $id_tipo;
        $objeto->nombre_empleado = $nombre_usuario;

        $retorno = $objeto->crearUsuario();

        if ($retorno == true) {
            $payload = json_encode(array("mensaje" => "Usuario creado con exito"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(201);  

        } else {
            $payload = json_encode(array("mensaje" => "Error al crear el usuario"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(400);
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['identificador'];
        $usuario = Usuario::ObtenerPorId($id);
        
        if($usuario != null)
        {
            $payload = json_encode(array("Usuario: " => $usuario));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(200);
        }
        else
        {
            $payload = json_encode(array("mensaje" => "USUARIO NO ENCONTRADO"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(404);
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {

        $objModificar = $args['identificador'];
        $obj = Usuario::ObtenerPorId($objModificar);

        if ($obj != null) {
            Usuario::CambiarEstadoUsuario($obj, 0);
            $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(200);
        } else {
            $payload = json_encode(array("mensaje" => "USUARIO INEXISTENTE"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(404);
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $objetoModificar = $args['identificador'];
        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];
        $id_tipo = $parametros['id_tipo'];
        $nombre_empleado = $parametros['nombre_empleado'];
        $objeto = Usuario::ObtenerPorId($objetoModificar);

        if ($objeto != null) {
            $objeto->usuario = $usuario;
            $objeto->clave = $clave;
            $objeto->id_tipo = $id_tipo;
            $objeto->nombre_empleado = $nombre_empleado;
            Usuario::modificarUsuario($objeto);
            $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(200);
        } else {
            $payload = json_encode(array("mensaje" => "USUARIO INEXISTENTE"));
            $response->getBody()->write($payload);
            $newResponse = $response->withStatus(404);        
        }

        return $newResponse
            ->withHeader('Content-Type', 'application/json');
    }


    ////VERIFICAR DE ACA PARA ABAJO
    /*
    7- De los empleados:
            a- Los días y horarios que se ingresaron al sistema.
    */
    public function TraerIngresoSistema($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $fecha1 = $parametros['fecha1'];
        $fecha2 = $parametros['fecha2'];
        $lista = Usuario::ObtenerIngresos($fecha1, $fecha2);

        if (count($lista) == 0) {
            $payload = json_encode(array("mensaje" => "No hay ingresos para esas fechas"));
        } else {
            foreach ($lista as $item) {
                echo ('<tr>');
                echo ('<td>' . $item->usuario . '</td>');
                echo ('<td>' . $item->nombre_usuario . '</td>');
                echo ('<td>' . $item->fecha_ultimo_login . '</td>');
            }
            $payload = json_encode("Ingresos devueltos");
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }  
}
