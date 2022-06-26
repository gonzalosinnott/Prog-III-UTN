<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

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

// Routes
$app->get('[/]', function (Request $request, Response $response) {    
  $response->getBody()->write("COMANDA SINNOTT SEGURA GONZALO");
  return $response;
});

//CREACION DE TOKEN
$app->get('/token', \TokenApi::class . ':ObtenerToken'); //OK

//LOGIN
$app->group('/login', function (RouteCollectorProxy $group) {
  $group->post('[/]', \UsuarioApi::class . ':LoginUsuario'); //OK
});

//ABM USUARIOS
$app->group('/usuarios', function (RouteCollectorProxy $group) {
  $group->get('/listarTodos[/]', \UsuarioApi::class . ':TraerTodos'); //OK
  $group->get('/listarPorTipo/{identificador}[/]', \UsuarioApi::class . ':TraerTodosPorTipo'); //OK 
  $group->get('/{identificador}[/]', \UsuarioApi::class . ':TraerUno');//OK
  $group->post('/crear[/]', \UsuarioApi::class . ':CargarUno');//OK 
  $group->put('/modificar/{identificador}[/]', \UsuarioApi::class . ':ModificarUno'); //OK 
  $group->delete('/borrar/{identificador}[/]', \UsuarioApi::class . ':BorrarUno'); //OK 
})->add(\UsuarioMiddleware::class . ':VerificarSocio')
  ->add(\TokenMiddleware::class . ':ValidarToken');

//ABM PRODUCTOS
$app->group('/productos', function (RouteCollectorProxy $group) {
  $group->get('/listarTodos[/]', \ProductoApi::class . ':TraerTodos'); //OK
  $group->get('/listarPorSector/{sector}[/]', \ProductoApi::class . ':TraerTodosPorSector'); //OK 
  $group->get('/{identificador}[/]', \ProductoApi::class . ':TraerUno'); //OK
  $group->post('/crear[/]', \ProductoApi::class . ':CargarUno'); //OK
  $group->put('/{identificador}[/]', \ProductoApi::class . ':ModificarUno'); //OK
  $group->delete('/{identificador}[/]', \ProductoApi::class . ':BorrarUno'); //OK
})->add(\TokenMiddleware::class . ':ValidarToken');

//ABM MESAS
$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('/listarTodos[/]', \MesaApi::class . ':TraerTodos'); //OK
  $group->get('/listarPorEstado/{estado}[/]', \MesaApi::class . ':TraerTodosPorEstado'); //OK
  $group->get('/{identificador}[/]', \MesaApi::class . ':TraerUno'); //OK
  $group->post('/crear[/]', \MesaApi::class . ':CargarUno'); //OK
  $group->put('/{identificador}[/]', \MesaApi::class . ':ModificarUno'); //OK
  $group->delete('/{identificador}[/]', \MesaApi::class . ':BorrarUno'); //OK
})->add(\TokenMiddleware::class . ':ValidarToken');

//ABM PEDIDOS
$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->get('/listarTodos[/]', \PedidoApi::class . ':TraerTodos'); //OK
  $group->get('/listarPorEstado/{estado}[/]', \PedidoApi::class . ':TraerTodosPorEstado'); //OK
  $group->get('/{identificador}[/]', \PedidoApi::class . ':TraerUno'); //OK 
  $group->post('/crear[/]', \PedidoApi::class . ':CargarUno'); //OK
  $group->put('/{identificador}', \PedidoApi::class . ':ModificarUno'); //OK
  $group->delete('/{identificador}', \PedidoApi::class . ':BorrarUno'); //OK
})->add(\TokenMiddleware::class . ':ValidarToken');

//ABM COMANDAS
$app->group('/comandas', function (RouteCollectorProxy $group) {
  $group->get('/listarTodos[/]', \ComandaApi::class . ':TraerTodos'); //OK
  $group->get('/listarPorEstado/{estado}[/]', \ComandaApi::class . ':TraerTodosPorEstado'); //OK
  $group->get('/{identificador}[/]', \ComandaApi::class . ':TraerUno'); //OK
  $group->post('/crear[/]', \ComandaApi::class . ':CargarUno'); //OK
  $group->put('/{identificador}', \ComandaApi::class . ':ModificarUno'); //OK
  $group->delete('/{identificador}', \ComandaApi::class . ':BorrarUno'); //OK
})->add(\TokenMiddleware::class . ':ValidarToken');

//CIRCUITO DE PEDIDO
//1- Una moza toma el pedido de: una milanesa a caballo, Dos hamburguesas de garbanzo, Una corona, Un Daikiri
$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->post('/crear[/]', \PedidoApi::class . ':CargarUno');  
})->add(\UsuarioMiddleware::class . ':VerificarMozo')
  ->add(\TokenMiddleware::class . ':ValidarToken');

  
$app->run();
