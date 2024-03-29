<?php
require_once './models/Mesa.php';
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';
class MesaController extends Mesa implements IApiUsable{
    public function TraerUno($request, $response, $args){
        $parametros = $request->getParsedBody();
        $codigo = $parametros['id'];
        $mesa = Mesa::obtenerMesa($codigo);
        $payload = json_encode($mesa);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args){
        $lista = Mesa::obtenerTodos();
        $payload = json_encode(array("listaMesas" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerOrdenadaMenorFactura($request, $response, $args){
        $mesas = Mesa::obtenerTodos();

        usort($mesas, function($mesa1, $mesa2) {
            if ($mesa1->estado !== 'cerrada' || $mesa1->cobro === null) {
                return 1; 
            }
            if ($mesa2->estado !== 'cerrada' || $mesa2->cobro === null) {
                return -1;
            }
            
            if ($mesa1->cobro < $mesa2->cobro) {
                return -1;
            } elseif ($mesa1->cobro > $mesa2->cobro) {
                return 1;
            } else {
                return 0;
            }
        });

        $payload = json_encode(array("listaMesas" => $mesas));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function CargarUno($request, $response, $args){
        $cookies = $request->getCookieParams();
        if(isset($cookies['JWT'])){
            $token = $cookies['JWT'];
            $datos = AutentificadorJWT::ObtenerData($token);
            $mesa = new Mesa();
            $mesa->codigo = self::generarCodigoMesa();
            $mesa->nombreMozo = $datos->nombre;
            $codigoPedido = self::generarCodigoMesa();
            $nombreCookie = $mesa->codigo.'_'.$datos->nombre.'_'.$datos->email;
            $tokenMesa = AutentificadorJWT::CrearToken(array('nombre' => $datos->nombre, 'mesa' => $mesa->codigo, 'codigoPedido' => $codigoPedido));
            setcookie($nombreCookie, $tokenMesa, time()+60*60*24*30, '/', 'localhost', false, true);
            $mesa->crearMesa();
            $payload = json_encode(array("mensaje" => "Mesa creada con exito - puede empezar a regitrar pedidos con el codigo [ $mesa->codigo ]"));
        }
        else{
            $payload = json_encode(array("mensaje" => "Datos invalidos"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args){
        $parametros = $request->getParsedBody();
        $mesa = Mesa::obtenerMesa($parametros['id']);
        Mesa::borrarMesa($mesa);
        $payload = json_encode(array("mensaje" => "Mesa borrada con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args){
        $parametros = $request->getParsedBody();
        $mesa = Mesa::obtenerMesa($parametros['id']);
        if(isset($parametros['nombreMozo'])){
            $mesa->nombreMozo = $parametros['nombreMozo'];
        }
        if(isset($parametros['estado'])){
            $mesa->estado = $parametros['estado'];
        }
        Mesa::modificarMesa($mesa);
        $payload = json_encode(array("mensaje" => "Mesa modificada con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function generarCodigoMesa(){
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $longitud = 16;
        $codigo = '';
        for ($i = 0; $i < $longitud; $i++) {
            $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        return $codigo;
    }

    public static function CerrarMesa($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $codigoMesa = $parametros['codigo'];
        if(isset($codigoMesa)){
            $listaPedidos = Pedido::obtenerPedidosPorMesa($codigoMesa);
            $mesa = Mesa::obtenerMesaCodigoMesa($codigoMesa);
            $precioACobrar = 0;
            foreach($listaPedidos as $pedido){
                if($pedido->estado == 'completado'){
                    $precioACobrar += $pedido->importe;
                }
            }
            $mesa->cobro = $precioACobrar;
            Mesa::modificarMesa($mesa);
            $payload = json_encode(array("mensaje" => "Mesa cerrada - Total a pagar: [ ".$precioACobrar." ]"));
        }
        else{
            $payload = json_encode(array("mensaje" => "No se encontro la mesa"));
        }
        Mesa::CobrarYCerrarMesa($parametros['codigo']);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function GetCobroEntreDosFechas($request, $response, $args){
        $parametros = $request->getQueryParams();
        $codigoMesa = $parametros['codigo'];
        $fechaEntrada = DateTime::createFromFormat('Y-m-d H:i:s', $parametros["fechaEntrada"]);
        $fechaSalida = DateTime::createFromFormat('Y-m-d H:i:s', $parametros["fechaSalida"]);
        if(isset($codigoMesa)){
            $listaPedidos = Pedido::obtenerPedidosPorMesa($codigoMesa);
            $mesa = Mesa::obtenerMesaCodigoMesa($codigoMesa);
            if ($mesa->estado == "cerrada")
            {
                $totalFacturado = 0;
                foreach($listaPedidos as $pedido){
                    if($pedido->estado == 'completado'){
                        $fechaPedido = DateTime::createFromFormat('Y-m-d H:i:s', $pedido->fechaInicio);
                        if ($fechaPedido >= $fechaEntrada && $fechaPedido <= $fechaSalida) {
                            $totalFacturado += $pedido->importe * $pedido->cantidad;
                        }
                    }
                }
                $payload = json_encode(array("mensaje" => "Total a facturado entre fechas: [ ".$totalFacturado." ]"));
            }
            else
            {
                $payload = json_encode(array("mensaje" => "La mesa no esta cerrada"));
            }
        }
        else{
            $payload = json_encode(array("mensaje" => "No se encontro la mesa"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}