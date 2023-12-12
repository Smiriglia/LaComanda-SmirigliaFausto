<?php
    require_once './models/Pedido.php';
    class AutenticadorPedidos{
        public static function ValidarMesaExistente($request, $handler){
            $parametros = $request->getParsedBody();
            if(isset($parametros['id'])){
                $id = $parametros['id'];
                $pedido = Pedido::obtenerPedido($id);
                if($pedido){
                    return $handler->handle($request);
                }
            }
            throw new Exception('Pedido no existente');
        }

        public static function ValidarCamposAlta($request, $handler){
            $parametros = $request->getParsedBody();
            if(isset($parametros['codigoPedido'], $parametros['idProducto'], $parametros['nombreCliente'], $parametros['cantidad'])){
                return $handler->handle($request);
            }
            throw new Exception('Campos Invalidos');
        }
        
        public static function ValidarCamposModificar($request, $handler){
            $parametros = $request->getParsedBody();
            if(isset($parametros['id'])){
                return $handler->handle($request);
            }
            throw new Exception('Campos Invalidos');
        }

        public static function ValidarEstado($request, $handler){
            $parametros = $request->getParsedBody();
            if(isset($parametros['id'])){
                $pedido = Pedido::obtenerPedidoIndividual($parametros['id']);
                if($pedido->estado == 'pendiente'){
                    return $handler->handle($request);
                }
                else{
                    throw new Exception('El pedido no se puede modificar porque se finalizo la preparacion o fue cancelado');
                }
            }
            throw new Exception('Campos Invalidos');
        }
        
        
    }

?>