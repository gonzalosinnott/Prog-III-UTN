<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';
require_once './db/AccesoDatos.php';
require_once './JWT/AutentificadorJWT.php';
require_once './middlewares/TokenMiddleware.php';
require_once './middlewares/UsuarioMiddleware.php';
require_once './API/TokenApi.php';
require_once './API/UsuarioApi.php';
require_once './API/ProductoApi.php';
require_once './API/MesaApi.php';
require_once './API/PedidoApi.php';
require_once './API/ComandaApi.php';
require_once './API/EncuestaApi.php';
require_once './API/ExtrasApi.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Set base path
$app->setBasePath('/app');

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Seteo Timezone
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);
error_reporting(E_ERROR |  E_PARSE);

// Routes
$app->get('[/]', function (Request $request, Response $response) {    
  $response->getBody()->write("COMANDA SINNOTT SEGURA GONZALO");
  return $response;
});

//CREACION DE TOKEN
$app->get('/token', \TokenApi::class . ':ObtenerToken'); 

//LOGIN
$app->group('/login', function (RouteCollectorProxy $group) {
  $group->post('[/]', \UsuarioApi::class . ':LoginUsuario'); 
});

//ABM USUARIOS
$app->group('/usuarios', function (RouteCollectorProxy $group) {
  $group->get('/listarTodos[/]', \UsuarioApi::class . ':TraerTodos'); 
  $group->get('/listarPorTipo/{identificador}[/]', \UsuarioApi::class . ':TraerTodosPorTipo'); 
  $group->get('/{identificador}[/]', \UsuarioApi::class . ':TraerUno');
  $group->post('/crear[/]', \UsuarioApi::class . ':CargarUno');
  $group->put('/modificar/{identificador}[/]', \UsuarioApi::class . ':ModificarUno'); 
  $group->delete('/borrar/{identificador}[/]', \UsuarioApi::class . ':BorrarUno'); 
})->add(\UsuarioMiddleware::class . ':VerificarSocio')
  ->add(\TokenMiddleware::class . ':ValidarToken');

//ABM PRODUCTOS
$app->group('/productos', function (RouteCollectorProxy $group) {
  $group->get('/listarTodos[/]', \ProductoApi::class . ':TraerTodos'); 
  $group->get('/listarPorSector/{sector}[/]', \ProductoApi::class . ':TraerTodosPorSector');  
  $group->get('/{identificador}[/]', \ProductoApi::class . ':TraerUno');
  $group->post('/crear[/]', \ProductoApi::class . ':CargarUno'); 
  $group->put('/{identificador}[/]', \ProductoApi::class . ':ModificarUno'); 
  $group->delete('/{identificador}[/]', \ProductoApi::class . ':BorrarUno'); 
})->add(\TokenMiddleware::class . ':ValidarToken');

//ABM MESAS
$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('/listarTodos[/]', \MesaApi::class . ':TraerTodos'); 
  $group->get('/listarPorEstado/{estado}[/]', \MesaApi::class . ':TraerTodosPorEstado');
  $group->get('/{identificador}[/]', \MesaApi::class . ':TraerUno'); 
  $group->post('/crear[/]', \MesaApi::class . ':CargarUno'); 
  $group->put('/{identificador}[/]', \MesaApi::class . ':ModificarUno'); 
  $group->delete('/{identificador}[/]', \MesaApi::class . ':BorrarUno'); 
})->add(\TokenMiddleware::class . ':ValidarToken');

//FUNCIONALIDADES MESAS
$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('/verEstadoMesas/Socio[/]', \MesaApi::class . ':VerEstadoMesas')->add(\UsuarioMiddleware::class . ':VerificarSocio'); //PUNTO 8
  $group->get('/verEstadoMesas/mesaMasUsada[/]', \MesaApi::class . ':TraerMesaMasUsada')->add(\UsuarioMiddleware::class . ':VerificarSocio'); //PUNTO 13
  $group->put('/cerrarMesa/{identificador}[/]', \MesaApi::class . ':CerrarMesa')->add(\UsuarioMiddleware::class . ':VerificarSocio'); //PUNTO 10
  $group->put('/liberarMesa/{identificador}[/]', \MesaApi::class . ':LiberarMesa')->add(\UsuarioMiddleware::class . ':VerificarMozo'); //PUNTO 10
})->add(\TokenMiddleware::class . ':ValidarToken');

//ABM PEDIDOS
$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->get('/listarTodos[/]', \PedidoApi::class . ':TraerTodos'); 
  $group->get('/listarPorEstado/{estado}[/]', \PedidoApi::class . ':TraerTodosPorEstado');
  $group->post('/crear[/]', \PedidoApi::class . ':CargarUno')->add(\UsuarioMiddleware::class . ':VerificarMozo'); //PUNTO 1 
  $group->get('/{identificador}[/]', \PedidoApi::class . ':TraerUno');  
  $group->put('/{identificador}', \PedidoApi::class . ':ModificarUno'); 
  $group->delete('/{identificador}', \PedidoApi::class . ':BorrarUno');
})->add(\TokenMiddleware::class . ':ValidarToken');

//FUNCIONALIDADES PEDIDOS
$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->post('/sacarFoto/{identificador}[/]', \PedidoApi::class . ':SacarFoto')->add(\UsuarioMiddleware::class . ':VerificarMozo'); //PUNTO 2
  $group->get('/confirmarPedido/{identificador}[/]', \PedidoApi::class . ':ConfirmarPedido')->add(\UsuarioMiddleware::class . ':VerificarMozo'); //PUNTO 1
  $group->post('/entregarPedido/{identificador}[/]', \PedidoApi::class . ':EntregarPedido')->add(\UsuarioMiddleware::class . ':VerificarMozo'); //PUNTO 7
  $group->post('/cerrarPedido/{identificador}[/]', \PedidoApi::class . ':CerrarPedido')->add(\UsuarioMiddleware::class . ':VerificarMozo'); //PUNTO 9
  $group->get('/verEstadoPedido/Cliente[/]', \PedidoApi::class . ':VerEstadoPedido'); //PUNTO 4
  $group->get('/verEstadoPedido/Socio[/]', \PedidoApi::class . ':VerEstadoPedidos')->add(\UsuarioMiddleware::class . ':VerificarSocio'); //PUNTO 5
  $group->get('/RealizarEncuesta/Cliente[/]', \PedidoApi::class . ':VerEstadoPedido'); //PUNTO 11
})->add(\TokenMiddleware::class . ':ValidarToken');

//ABM COMANDAS
$app->group('/comandas', function (RouteCollectorProxy $group) {
  $group->get('/listarTodos[/]', \ComandaApi::class . ':TraerTodos'); 
  $group->get('/listarPorEstado/{estado}[/]', \ComandaApi::class . ':TraerTodosPorEstado'); 
  $group->get('/{identificador}[/]', \ComandaApi::class . ':TraerUno'); 
  $group->post('/crear[/]', \ComandaApi::class . ':CargarUno')->add(\UsuarioMiddleware::class . ':VerificarMozo'); //PUNTO 1
  $group->put('/{identificador}', \ComandaApi::class . ':ModificarUno'); 
  $group->delete('/{identificador}', \ComandaApi::class . ':BorrarUno');
})->add(\TokenMiddleware::class . ':ValidarToken');

//FUNCIONALIDADES COMANDAS
$app->group('/comandas/administrar', function (RouteCollectorProxy $group) {
  $group->get('/listarPendientes[/]', \ComandaApi::class . ':TraerPendientes')->add(\UsuarioMiddleware::class . ':ListarPedidos'); //PUNTO 3 
  $group->put('/prepararPedido[/]', \ComandaApi::class . ':PrepararPedido')->add(\UsuarioMiddleware::class . ':AdministrarPedidos'); //PUNTO 3 
  $group->get('/listarEnPreparacion[/]', \ComandaApi::class . ':TraerEnPreparacion')->add(\UsuarioMiddleware::class . ':ListarPedidos'); //PUNTO 6
  $group->put('/entregarPedido[/]', \ComandaApi::class . ':EntregarPedido')->add(\UsuarioMiddleware::class . ':AdministrarPedidos'); //PUNTO 6
})->add(\TokenMiddleware::class . ':ValidarToken');

//ENCUESTAS
$app->group('/encuestas', function (RouteCollectorProxy $group) {
  $group->post('/realizarEncuesta[/]', \EncuestaApi::class . ':CargarUno'); //PUNTO 11
  $group->get('/verEncuestas[/]', \EncuestaApi::class . ':TraerTodos')->add(\UsuarioMiddleware::class . ':VerificarSocio'); //PUNTO 11
  $group->get('/verMejoresComentarios[/]', \EncuestaApi::class . ':TraerMejores')->add(\UsuarioMiddleware::class . ':VerificarSocio'); //PUNTO 11
})->add(\TokenMiddleware::class . ':ValidarToken');

//EXTRAS
$app->group('/extras', function (RouteCollectorProxy $group) {
  $group->post('/cargarProductoCSV[/]', \ExtrasApi::class . ':CargarProductoCSV'); 
  $group->get('/verComandasCSV[/]', \ExtrasApi::class . ':TraerComandasCSV');
  $group->get('/verComandasPDF[/]', \ExtrasApi::class . ':TraerComandasPDF');  
})->add(\TokenMiddleware::class . ':ValidarToken');

$app->run();
