<?php
require_once './models/Pedido.php';
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';
class PedidoController extends Pedido implements IApiUsable{
    public function TraerUno($request, $response, $args){
        $parametros = $request->getParsedBody();
        $pedido = Pedido::obtenerPedido($parametros['codigoPedido']);
        $payload = json_encode($pedido);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args){
        $lista = Pedido::obtenerTodos();
        $payload = json_encode(array("listaPedidos" => $lista));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function CargarUno($request, $response, $args){
        $parametros = $request->getParsedBody();
        $cookies = $request->getCookieParams();
        $tokenUsuario = AutentificadorJWT::ObtenerData($cookies['JWT']);
        $tokenMesa = AutentificadorJWT::ObtenerData($cookies[$parametros['codigoPedido'].'_'.$tokenUsuario->nombre.'_'.$tokenUsuario->email]);
        $producto = Producto::obtenerProducto($parametros['idProducto']);
        $pedido = new Pedido();
        $pedido->codigoPedido = $tokenMesa->codigoPedido;
        $pedido->idMesa = $tokenMesa->mesa;
        $pedido->idProducto = $parametros['idProducto'];
        $pedido->sector = self::ChequearSector($producto->tipo);
        $pedido->cantidad = $parametros['cantidad'];
        $pedido->nombreCliente = $parametros['nombreCliente'];
        $pedido->importe = $parametros['cantidad'] * $producto->precio;
        $pedido->crearPedido();
        $payload = json_encode(array("mensaje" => "Pedido creado con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args){
        $parametros = $request->getParsedBody();
        if(isset($parametros['id'])){
            $pedido = Pedido::obtenerPedidoIndividual($parametros['id']);
            Pedido::borrarPedido($pedido);
            $payload = json_encode(array("mensaje" => "Pedido borrado con exito"));
        }
        else{
            $payload = json_encode(array("mensaje" => "Debe ingresar un id de pedido valido"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args){
        $parametros = $request->getParsedBody();
        $pedido = Pedido::obtenerPedidoIndividual($parametros['id']);
        if(isset($parametros['nombreCliente'])){
            $pedido->nombreCliente = $parametros['nombreCliente'];
        }
        if(isset($parametros['cantidad'])){
            $pedido->cantidad = $parametros['cantidad'];
            $producto = Producto::obtenerProducto($parametros['idProducto']);
            $pedido->importe = $producto->precio * $parametros['cantidad'];
        }
        if(isset($parametros['idProducto'])){
            Pedido::modificarPedido($pedido, $parametros['idProducto']);
        }
        else{
            Pedido::modificarPedido($pedido, false);
        }
        $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function generarCodigoPedido(){
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $longitud = 16;
        $codigo = '';
        for ($i = 0; $i < $longitud; $i++) {
            $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        return $codigo;
    }
    
    public static function TraerTodosPorSector($request, $response, $args) {
        $cookie = $request->getCookieParams();
        if(isset($cookie['JWT'])){
            $token = $cookie['JWT'];
            $datos = AutentificadorJWT::ObtenerData($token);
            if($datos->rol == 'cocinero' || $datos->rol == 'cocinera'){
                $lista = Pedido::obtenerTodosPorSector('cocina');
            }
            if($datos->rol == 'barman'){
                $lista = Pedido::obtenerTodosPorSector('barra');
            }
            if($datos->rol == 'maestro pastelero' || $datos->rol == 'maestro pastelera'){
                $lista = Pedido::obtenerTodosPorSector('candybar');
            }
            $payload = json_encode(array("listaPedidos" => $lista));
        }
        else{
            $payload = json_encode(array("listaPedidos" => 'No hay pedidos para tu sector'));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function RecibirPedidos($request, $response, $args) {
        $idPedido = $args['idPedido'];
        $pedido = Pedido::obtenerPedidoIndividual($idPedido);
        Pedido::updatePedidoPendiente($pedido);
        $payload = json_encode(array("mensaje" => 'Comenzo la preparacion del pedido'));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function PrepararPedido($request, $response, $args) {
        $idPedido = $args['idPedido'];
        $pedido = Pedido::obtenerPedidoIndividual($idPedido);
        Pedido::updatePedidoEnPreparacion($pedido);
        $payload = json_encode(array("mensaje" => 'Finalizo la preparacion del pedido'));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function EntregarPedidoFinalizado($request, $response, $args) {
        $idPedido = $args['idPedido'];
        Pedido::LlevarPedido($idPedido);
        $payload = json_encode(array("mensaje" => 'Que lo disfrutes'));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function DescargarCSV($request, $response, $args) {
        $pedidos = Pedido::obtenerTodosFinalizados('completado');
        $fecha = new DateTime(date('Y-m-d'));
        $path = date_format($fecha, 'Y-m-d').'pedidos_completados.csv';
        $archivo = fopen($path, 'w');
        $encabezado = array('id','codigoPedido','idMesa','idProducto','nombreCliente','sector','estado','importe','cantidad','fechaInicio','fechaCierre');
        fputcsv($archivo, $encabezado);
        foreach($pedidos as $pedido){
            $linea = array($pedido->id, $pedido->codigoPedido, $pedido->idMesa, $pedido->idProducto, $pedido->nombreCliente, $pedido->sector, $pedido->estado, $pedido->importe, $pedido->cantidad, $pedido->fechaInicio, $pedido->fechaCierre);
            fputcsv($archivo, $linea);
        }
        $payload = json_encode(array("mensaje" => 'Archivo de Pedidos del dia de la fecha creado exitosamente'));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');

    }
    
    public static function ChequearSector($tipo){
        if($tipo === 'comida'){
            return 'cocina';
        }
        else if($tipo === 'bebida'){
            return 'barra';
        }
        else if($tipo === 'postre'){
            return 'candybar';
        }
    }

    public function CalcularPromedioIngresos30Dias($request, $response, $args)
    {
        $fechaActual = date("Y-m-d H:i:s");  // Fecha y hora actuales en formato "YYYY-MM-DD HH:mm:ss"
        $fechaActualObj = new DateTime($fechaActual);
        $fechaLimite = $fechaActualObj->modify('-30 days');
        $pedidos = Pedido::obtenerTodosFinalizados("completado");
        $acumulador = 0;
        foreach ($pedidos as $pedido)
        {
            $fechaCierre = new DateTime($pedido->fechaCierre);
            if($fechaCierre >= $fechaLimite)
            {
                $acumulador += $pedido->importe;
            }
        }
        $promedio = $acumulador / 30;


        $payload = json_encode(array("mensaje" => "El importe promedio en los ultimos 30 dias fue de: " . $promedio));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    } 

    
}