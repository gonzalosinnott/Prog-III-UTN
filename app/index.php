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

error_reporting(E_ERROR |  E_PARSE);

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

//FUNCIONALIDADES MESAS
$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('/verEstadoMesas/Socio[/]', \MesaApi::class . ':VerEstadoMesas')->add(\UsuarioMiddleware::class . ':VerificarSocio');
})->add(\TokenMiddleware::class . ':ValidarToken');

//ABM PEDIDOS
$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->get('/listarTodos[/]', \PedidoApi::class . ':TraerTodos'); //OK
  $group->get('/listarPorEstado/{estado}[/]', \PedidoApi::class . ':TraerTodosPorEstado');
  $group->post('/crear[/]', \PedidoApi::class . ':CargarUno')->add(\UsuarioMiddleware::class . ':VerificarMozo');  //OK
  $group->get('/{identificador}[/]', \PedidoApi::class . ':TraerUno'); //OK 
  $group->put('/{identificador}', \PedidoApi::class . ':ModificarUno'); //OK
  $group->delete('/{identificador}', \PedidoApi::class . ':BorrarUno'); //OK
})->add(\TokenMiddleware::class . ':ValidarToken');

//FUNCIONALIDADES PEDIDOS
$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->post('/sacarFoto/{identificador}[/]', \PedidoApi::class . ':SacarFoto')->add(\UsuarioMiddleware::class . ':VerificarMozo'); //OK
  $group->get('/confirmarPedido/{identificador}[/]', \PedidoApi::class . ':ConfirmarPedido')->add(\UsuarioMiddleware::class . ':VerificarMozo'); //OK
  $group->post('/entregarPedido/{identificador}[/]', \PedidoApi::class . ':EntregarPedido')->add(\UsuarioMiddleware::class . ':VerificarMozo'); //OK
  $group->get('/verEstadoPedido/Cliente[/]', \PedidoApi::class . ':VerEstadoPedido'); //OK
  $group->get('/verEstadoPedido/Socio[/]', \PedidoApi::class . ':VerEstadoPedidos')->add(\UsuarioMiddleware::class . ':VerificarSocio');
})->add(\TokenMiddleware::class . ':ValidarToken');

//ABM COMANDAS
$app->group('/comandas', function (RouteCollectorProxy $group) {
  $group->get('/listarTodos[/]', \ComandaApi::class . ':TraerTodos'); //OK
  $group->get('/listarPorEstado/{estado}[/]', \ComandaApi::class . ':TraerTodosPorEstado'); //OK
  $group->get('/{identificador}[/]', \ComandaApi::class . ':TraerUno'); //OK
  $group->post('/crear[/]', \ComandaApi::class . ':CargarUno')->add(\UsuarioMiddleware::class . ':VerificarMozo'); //OK
  $group->put('/{identificador}', \ComandaApi::class . ':ModificarUno'); //OK
  $group->delete('/{identificador}', \ComandaApi::class . ':BorrarUno'); //OK
})->add(\TokenMiddleware::class . ':ValidarToken');

//FUNCIONALIDADES COMANDAS
$app->group('/comandas/listarPendientes', function (RouteCollectorProxy $group) {
  $group->get('/Bartender', \ComandaApi::class . ':TraerPendientesBartender')->add(\UsuarioMiddleware::class . ':VerificarBartender'); //OK  
  $group->get('/Cervecero', \ComandaApi::class . ':TraerPendientesCervecero')->add(\UsuarioMiddleware::class . ':VerificarCervecero'); //OK 
  $group->get('/Cocina', \ComandaApi::class . ':TraerPendientesCocina')->add(\UsuarioMiddleware::class . ':VerificarCocinero'); //OK 
  $group->get('/Candybar', \ComandaApi::class . ':TraerPendientesCandybar')->add(\UsuarioMiddleware::class . ':VerificarCocinero'); //OK 
})->add(\TokenMiddleware::class . ':ValidarToken');

$app->group('/comandas/prepararPedido', function (RouteCollectorProxy $group) {
  $group->put('/Bartender/{identificador}', \ComandaApi::class . ':PrepararPedidoBartender')->add(\UsuarioMiddleware::class . ':VerificarBartender'); //OK  
  $group->put('/Cervecero/{identificador}', \ComandaApi::class . ':PrepararPedidoCervecero')->add(\UsuarioMiddleware::class . ':VerificarCervecero'); //OK 
  $group->put('/Cocina/{identificador}', \ComandaApi::class . ':PrepararPedidoCocina')->add(\UsuarioMiddleware::class . ':VerificarCocinero'); //OK 
  $group->put('/Candybar/{identificador}', \ComandaApi::class . ':PrepararPedidoCandybar')->add(\UsuarioMiddleware::class . ':VerificarCocinero'); //OK
})->add(\TokenMiddleware::class . ':ValidarToken');

$app->group('/comandas/listarEnPreparacion', function (RouteCollectorProxy $group) {
  $group->get('/Bartender', \ComandaApi::class . ':TraerEnPreparacionBartender')->add(\UsuarioMiddleware::class . ':VerificarBartender'); //OK  
  $group->get('/Cervecero', \ComandaApi::class . ':TraerEnPreparacionCervecero')->add(\UsuarioMiddleware::class . ':VerificarCervecero'); //OK 
  $group->get('/Cocina', \ComandaApi::class . ':TraerEnPreparacionCocina')->add(\UsuarioMiddleware::class . ':VerificarCocinero'); //OK 
  $group->get('/Candybar', \ComandaApi::class . ':TraerEnPreparacionCandybar')->add(\UsuarioMiddleware::class . ':VerificarCocinero'); //OK 
})->add(\TokenMiddleware::class . ':ValidarToken');

$app->group('/comandas/entregarPedido', function (RouteCollectorProxy $group) {
  $group->put('/Bartender/{identificador}', \ComandaApi::class . ':EntregarPedidoBartender')->add(\UsuarioMiddleware::class . ':VerificarBartender'); //OK  
  $group->put('/Cervecero/{identificador}', \ComandaApi::class . ':EntregarPedidoCervecero')->add(\UsuarioMiddleware::class . ':VerificarCervecero'); //OK 
  $group->put('/Cocina/{identificador}', \ComandaApi::class . ':EntregarPedidoCocina')->add(\UsuarioMiddleware::class . ':VerificarCocinero'); //OK 
  $group->put('/Candybar/{identificador}', \ComandaApi::class . ':EntregarPedidoCandybar')->add(\UsuarioMiddleware::class . ':VerificarCocinero'); //OK
})->add(\TokenMiddleware::class . ':ValidarToken');

/*
CIRCUITO DE PEDIDO:

//1- Una moza toma el pedido de: una milanesa a caballo, Dos hamburguesas de garbanzo, Una corona, Un Daikiri

  $app->group('/pedidos', function (RouteCollectorProxy $group)
    $group->post('/crear[/]', \PedidoApi::class . ':CargarUno')->add(\UsuarioMiddleware::class . ':VerificarMozo');
  })->add(\TokenMiddleware::class . ':ValidarToken');

  $app->group('/comandas', function (RouteCollectorProxy $group)
    $group->post('/crear[/]', \ComandaApi::class . ':CargarUno')->add(\UsuarioMiddleware::class . ':VerificarMozo');
  })->add(\TokenMiddleware::class . ':ValidarToken'); 

  $app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->get('/confirmarPedido/{identificador}[/]', \PedidoApi::class . ':ConfirmarPedido')->add(\UsuarioMiddleware::class . ':VerificarMozo');
  })->add(\TokenMiddleware::class . ':ValidarToken');

//2- El mozo saca una foto de la mesa y lo relaciona con el pedido.

  $app->group('/pedidos', function (RouteCollectorProxy $group)
    $group->post('/sacarFoto/{identificador}[/]', \PedidoApi::class . ':SacarFoto')->add(\UsuarioMiddleware::class . ':VerificarMozo');
  })->add(\TokenMiddleware::class . ':ValidarToken');

//3- Cada empleado responsable de cada producto del pedido , debe:
//A-Listar todos los productos pendientes de este tipo de empleado.
//B-Debe cambiar el estado a “en preparación” y agregarle el tiempo de preparación.

  $app->group('/comandas/listarPendientes', function (RouteCollectorProxy $group) {
    $group->get('/Bartender', \ComandaApi::class . ':TraerPendientesBartender')->add(\UsuarioMiddleware::class . ':VerificarBartender'); //OK  
    $group->get('/Cervecero', \ComandaApi::class . ':TraerPendientesCervecero')->add(\UsuarioMiddleware::class . ':VerificarCervecero'); //OK 
    $group->get('/Cocina', \ComandaApi::class . ':TraerPendientesCocina')->add(\UsuarioMiddleware::class . ':VerificarCocinero'); //OK 
    $group->get('/Candybar', \ComandaApi::class . ':TraerPendientesCandybar')->add(\UsuarioMiddleware::class . ':VerificarCocinero'); //OK 
  })->add(\TokenMiddleware::class . ':ValidarToken');

  $app->group('/comandas/prepararPedido', function (RouteCollectorProxy $group) {
    $group->put('/Bartender/{identificador}', \ComandaApi::class . ':PrepararPedidoBartender')->add(\UsuarioMiddleware::class . ':VerificarBartender');  
    $group->put('/Cervecero/{identificador}', \ComandaApi::class . ':PrepararPedidoCervecero')->add(\UsuarioMiddleware::class . ':VerificarCervecero'); 
    $group->put('/Cocina/{identificador}', \ComandaApi::class . ':PrepararPedidoCocina')->add(\UsuarioMiddleware::class . ':VerificarCocinero'); 
    $group->put('/Candybar/{identificador}', \ComandaApi::class . ':PrepararPedidoCandybar')->add(\UsuarioMiddleware::class . ':VerificarCocinero'); 
  })->add(\TokenMiddleware::class . ':ValidarToken');

//4- El cliente ingresa el código de la mesa junto con el número de pedido y ve el tiempo de demora de su pedido.

  $app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->get('/verEstadoPedido/Cliente[/]', \PedidoApi::class . ':verEstadoPedido'); //OK
  })->add(\TokenMiddleware::class . ':ValidarToken');

//5- Alguno de los socios pide el listado de pedidos y el tiempo de demora de ese pedido.

  $app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->get('/verEstadoPedido/Socio[/]', \PedidoApi::class . ':VerEstadoPedidos')->add(\UsuarioMiddleware::class . ':VerificarSocio');
  })->add(\TokenMiddleware::class . ':ValidarToken');

//6- Cada empleado responsable de cada producto del pedido, debe:
//Listar todos los productos pendientes de este tipo de empleado
//Debe cambiar el estado a “listo para servir” .

  $app->group('/comandas/listarEnPreparacion', function (RouteCollectorProxy $group) {
    $group->get('/Bartender', \ComandaApi::class . ':TraerEnPreparacionBartender')->add(\UsuarioMiddleware::class . ':VerificarBartender');  
    $group->get('/Cervecero', \ComandaApi::class . ':TraerEnPreparacionCervecero')->add(\UsuarioMiddleware::class . ':VerificarCervecero'); 
    $group->get('/Cocina', \ComandaApi::class . ':TraerEnPreparacionCocina')->add(\UsuarioMiddleware::class . ':VerificarCocinero'); 
    $group->get('/Candybar', \ComandaApi::class . ':TraerEnPreparacionCandybar')->add(\UsuarioMiddleware::class . ':VerificarCocinero'); 
  })->add(\TokenMiddleware::class . ':ValidarToken');

  $app->group('/comandas/entregarPedido', function (RouteCollectorProxy $group) {
    $group->put('/Bartender/{identificador}', \ComandaApi::class . ':EntregarPedidoBartender')->add(\UsuarioMiddleware::class . ':VerificarBartender'); 
    $group->put('/Cervecero/{identificador}', \ComandaApi::class . ':EntregarPedidoCervecero')->add(\UsuarioMiddleware::class . ':VerificarCervecero'); 
    $group->put('/Cocina/{identificador}', \ComandaApi::class . ':EntregarPedidoCocina')->add(\UsuarioMiddleware::class . ':VerificarCocinero');
    $group->put('/Candybar/{identificador}', \ComandaApi::class . ':EntregarPedidoCandybar')->add(\UsuarioMiddleware::class . ':VerificarCocinero');
  })->add(\TokenMiddleware::class . ':ValidarToken');

//7- La moza se fija los pedidos que están listos para servir , cambia el estado de la mesa,
  $app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->post('/entregarPedido/{identificador}[/]', \PedidoApi::class . ':EntregarPedido')->add(\UsuarioMiddleware::class . ':VerificarMozo');    
  })->add(\TokenMiddleware::class . ':ValidarToken');

//8- Alguno de los socios pide el listado de las mesas y sus estados .

  $app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->get('/verEstadoMesas/Socio[/]', \MesaApi::class . ':VerEstadoMesas')->add(\UsuarioMiddleware::class . ':VerificarSocio');
  })->add(\TokenMiddleware::class . ':ValidarToken');
*/

$app->run();
