<?php

include_once './JWT/AutentificadorJWT.php';
include_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';


class UsuarioApi extends Usuario implements IApiUsable

#region ABM
{

    public function TraerTodos($request, $response, $args)
    {
        $objetos = Usuario::obtenerTodos();
        $payload = json_encode(array("Lista: " => $objetos));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodosPorTipo($request, $response, $args)
    {
        $identificador = $args['identificador'];
        $usuarios = Usuario::ObtenerPorTipo($identificador);
        $payload = json_encode(array("Lista: " => $usuarios));

        $response->getBody()->write($payload);
        return $response
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
        $fecha_registro = $parametros['fecha_registro'];


        $objeto = new Usuario();

        $objeto->usuario = $usuario;
        $objeto->clave = $clave;
        $objeto->id_tipo = $id_tipo;
        $objeto->nombre_usuario = $nombre_usuario;
        $objeto->estado = $estado;
        $objeto->fecha_registro = $fecha_registro;



        if ($objeto->crearUsuario() == true) {
            $payload = json_encode(array("mensaje" => "Usuario creado con exito"));
        } else {
            $payload = json_encode(array("mensaje" => "Error al crear el usuario"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['identificador'];
        $usuario = Usuario::ObtenerPorId($id);
        if($usuario != null){
            $payload = json_encode(array("Usuario: " => $usuario));
        }else{
            $payload = json_encode(array("mensaje" => "No se encontro el usuario"));
        }
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {

        $objModificar = $args['identificador'];
        $obj = Usuario::ObtenerPorId($objModificar);

        if ($obj != null) {
            Usuario::CambiarEstadoUsuario($obj, 0);
            $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));
        } else {
            $payload = json_encode(array("mensaje" => "Error al borrar el usuario"));
        }
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $objetoModificar = $args['identificador'];
        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];
        $id_tipo = $parametros['id_tipo'];
        $nombre_usuario = $parametros['nombre_usuario'];
        $estado = $parametros['estado'];
        $fecha_registro = $parametros['fecha_registro'];
        $fecha_ultimo_login = $parametros['fecha_ultimo_login'];


        $objeto = Usuario::ObtenerPorId($objetoModificar);

        if ($objeto != null) {
            $objeto->usuario = $usuario;
            $objeto->clave = $clave;
            $objeto->id_tipo = $id_tipo;
            $objeto->nombre_usuario = $nombre_usuario;
            $objeto->estado = $estado;
            $objeto->fecha_registro = $fecha_registro;
            $objeto->fecha_ultimo_login = $fecha_ultimo_login;


            Usuario::modificarUsuario($objeto);
            $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
        } else {
            $payload = json_encode(array("mensaje" => "Error al modificar el usuario"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }


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

    public function LoginUsuario($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $usuario = $parametros["usuario"];
        $clave = $parametros["clave"];
        $retorno = Usuario::Login($usuario, $clave);

        if ($retorno != null) {
            $token = AutentificadorJWT::crearToken($usuario);
            Usuario::ActualizarFechaLogin($retorno["id_empleado"]);
            $respuesta = json_encode(array("Estado" => "OK", "Mensaje" => "Logueado exitosamente.", "Token" => $token, "Nombre_usuario" => $retorno["nombre_empleado"]));
        } else {
            $respuesta = json_encode(array(["Estado" => "ERROR", "Mensaje" => "Usuario o clave invalidos."]));
        }
        $response->getBody()->write($respuesta);
        return $response->withHeader('Content-Type', 'application/json');
    }   
}
