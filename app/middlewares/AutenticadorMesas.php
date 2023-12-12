<?php
    require_once './models/Mesa.php';
    class AutenticadorMesas{
        public static function ValidarMesa($request, $handler){
            $parametros = $request->getParsedBody();
            if(isset($parametros['id'])){
                $mesa = Mesa::obtenerMesa($parametros['id']);
                if($mesa){
                    return $handler->handle($request);
                }
            }
            throw new Exception('Mesa no existente');
        }

        public static function ValidarCampos($request, $handler){
            $parametros = $request->getParsedBody();
            if(isset($parametros['codigo'])){
                $codigo = $parametros['codigo'];
                $mesa = Mesa::obtenerMesa($codigo);
                if(self::ValidarMesaExistente($mesa)){
                    return $handler->handle($request);
                }
            }
            throw new Exception('Campos Invalidos');
        }

        public static function ValidarMesaExistente($mesa){
            if($mesa){
                return true;
            }
            return false;
        }

    }

?>