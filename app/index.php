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

require_once './API/TokenApi.php';


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
$app->get('/token', \TokenApi::class . ':ObtenerToken');

//LOGIN DE USUARIO




$app->run();
