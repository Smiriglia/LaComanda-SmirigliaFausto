<?php
// php -S localhost:666 -t app
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
require_once './middlewares/AutentificadorJWT.php';
require_once './middlewares/AutenticadorUsuarios.php';
require_once './middlewares/AutenticadorProductos.php';
require_once './middlewares/AutenticadorMesas.php';
require_once './middlewares/AutenticadorPedidos.php';
require_once './middlewares/log_middleware.php';
require_once './middlewares/Logger.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/log_transacciones_controller.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();
$app->addBodyParsingMiddleware();
// Add error middleware
$errorMiddleware = function ($request, $exception, $displayErrorDetails) use ($app) {
    $statusCode = 400;
    $errorMessage = $exception->getMessage();  
    $response = $app->getResponseFactory()->createResponse($statusCode);
    $response->getBody()->write(json_encode(['error' => $errorMessage]));
    return $response->withHeader('Content-Type', 'application/json');
};
  
$app->addErrorMiddleware(true, true, true)->setDefaultErrorHandler($errorMiddleware);

$app->get('/admin', function (Request $request, Response $response){
    $usuario = new Usuario();
    $usuario->nombre = 'admin';
    $usuario->email = 'ejemplo@gmail.com';
    $usuario->clave = '1234';
    $usuario->rol = 'socio';
    $usuario->estado = 'activo';
    $usuario->crearUsuario();
    $response->getBody()->write('Creado super usario');
    return $response->withHeader('Content-Type', 'application/json');
});

$app->group('/sesion', function (RouteCollectorProxy $group) {
    $group->post('[/]', \Logger::class.'::Loguear');
    $group->get('[/]', \Logger::class.'::Salir');
})
->add(\Logger::class.'::LimpiarCoockieUsuario');

// Routes
$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':TraerTodos');
    $group->get('/id', \UsuarioController::class . ':TraerUno');   
    $group->post('[/]', \UsuarioController::class . ':CargarUno')
    ->add(\AutenticadorUsuario::class.':ValidarCampos');
    $group->put('[/]', \UsuarioController::class . ':ModificarUno')
    ->add(\AutenticadorUsuario::class.':ValidarCampos');
    $group->delete('[/]', \UsuarioController::class . ':BorrarUno');
})
->add(\AutenticadorUsuario::class.':ValidarPermisosDeRol')
->add(\Logger::class.':ValidarSesionIniciada');

$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class.':TraerTodos');
    $group->get('/id', \ProductoController::class.':TraerUno');
    $group->post('[/]', \ProductoController::class.':CargarUno')
    ->add(\AutenticadorProductos::class.':ValidarCamposProductos')
    ->add(\AutenticadorUsuario::class.':ValidarPermisosDeRol')
    ->add(\Logger::class.':ValidarSesionIniciada');
    $group->put('[/]', ProductoController::class.':ModificarUno')
    ->add(\AutenticadorProductos::class.':ValidarCamposProductos')
    ->add(\AutenticadorUsuario::class.':ValidarPermisosDeRol')
    ->add(\Logger::class.':ValidarSesionIniciada');    
    $group->delete('[/]', \ProductoController::class.':BorrarUno')
    ->add(\AutenticadorUsuario::class.':ValidarPermisosDeRol')
    ->add(\Logger::class.':ValidarSesionIniciada');
});

$app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class.':TraerTodos')
    ->add(\AutenticadorUsuario::class.':ValidarPermisosDeRolDoble');
    $group->get('/id', \MesaController::class.':TraerUno')
    ->add(\AutenticadorUsuario::class.':ValidarPermisosDeRolDoble');
    $group->post('[/]', \MesaController::class.':CargarUno')
    ->add(\AutenticadorUsuario::class.':ValidarPermisosDeRol');
    $group->put('[/]', \MesaController::class.':ModificarUno')
    ->add(\AutenticadorMesas::class.':ValidarMesa')
    ->add(\AutenticadorUsuario::class.':ValidarPermisosDeRol');
    $group->delete('[/]', \MesaController::class.':BorrarUno')
    ->add(\AutenticadorMesas::class.':ValidarMesa')
    ->add(\AutenticadorUsuario::class.':ValidarPermisosDeRol');
    
})->add(\Logger::class.':ValidarSesionIniciada');

$app->group('/cobrar', function (RouteCollectorProxy $group) {
    $group->post('[/]', \MesaController::class.':CerrarMesa')
    ->add(\AutenticadorUsuario::class.':ValidarPermisosDeRolDoble');    
});

$app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \PedidoController::class.':TraerTodos')
    ->add(\AutenticadorUsuario::class.':ValidarPermisosDeRolDoble');
    $group->get('/codigo', \PedidoController::class.':TraerUno')
    ->add(\AutenticadorUsuario::class.':ValidarPermisosDeRolDoble');
    $group->post('[/]', \PedidoController::class.':CargarUno')
    ->add(\AutenticadorPedidos::class.':ValidarCamposAlta')
    ->add(function ($request, $handler){
        return \AutenticadorUsuario::ValidarPermisosDeRol($request, $handler, 'mozo');
    });
    $group->put('[/]', \PedidoController::class.':ModificarUno')
    ->add(\AutenticadorPedidos::class.':ValidarCamposModificar')
    ->add(\AutenticadorPedidos::class.':ValidarEstado')
    ->add(\AutenticadorUsuario::class.':ValidarPermisosDeRolDoble');
    $group->delete('[/]', \PedidoController::class.':BorrarUno')
    ->add(\AutenticadorUsuario::class.':ValidarPermisosDeRol');
    $group->get('/por/sector', \PedidoController::class.':TraerTodosPorSector');
    $group->get('/sector/preparar/{idPedido}', \PedidoController::class.':RecibirPedidos');
    $group->get('/sector/preparado/{idPedido}', \PedidoController::class.':PrepararPedido')
    ->add(\AutenticadorUsuario::class.':VerificarUsuario');
    $group->get('/entregar/pedido/{idPedido}', \PedidoController::class.':EntregarPedidoFinalizado')
    ->add(\AutenticadorUsuario::class.':VerificarUsuario');    
})->add(\Logger::class.':ValidarSesionIniciada');

$app->group('/archivos', function (RouteCollectorProxy $group) {
    $group->post('/cargarProductos', \ProductoController::class.'::CargarCSV');
    $group->get('/descargarPedidos', \PedidoController::class.'::DescargarCSV');
    $group->get('/descargarUsuarios', \UsuarioController::class.':DescargarPDF');
})
->add(\AutenticadorUsuario::class.':ValidarPermisosDeRol')
->add(\Logger::class.':ValidarSesionIniciada');

$app->group('/estadisticas', function (RouteCollectorProxy $group) {
    $group->get('/promedio', \PedidoController::class.':CalcularPromedioIngresos30Dias');
})
->add(\AutenticadorUsuario::class.':ValidarPermisosDeRol')
->add(\Logger::class.':ValidarSesionIniciada');

$app->get('/transacciones', \LogTransaccionesController::class.':GetTransacciones')
->add(\AutenticadorUsuario::class.':ValidarPermisosDeRol')
->add(\Logger::class.':ValidarSesionIniciada');


$app->get('[/]', function (Request $request, Response $response) {
    $payload = json_encode(array("mensaje" => "Prueba de conex"));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});




$app->add(\LogMiddleware::class.':LogTransaccion');

$app->run();
