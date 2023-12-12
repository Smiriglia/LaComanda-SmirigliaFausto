<?php
    require_once './models/Producto.php';
    class AutenticadorProductos{
        public static function ValidarCamposProductos($request, $handler){
            $parametros = $request->getParsedBody();
            if(isset($parametros['nombre']) || isset($parametros['tipo']) || isset($parametros['precio']) || isset($parametros['tiempoPreparacion'])){
                return $handler->handle($request);
            }
            throw new Exception('Campos Invalidos');
        }
        
    }

?>