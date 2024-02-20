<?php
    require_once './models/Comentario.php';
    class AutenticadorComentarios{
        public static function ValidarCamposComentario($request, $handler){
            $parametros = $request->getParsedBody();
            if(isset($parametros['codigoMesa']) && isset($parametros['puntaje']) && isset($parametros['comentario'])){
                if ($parametros['puntaje'] > 0 && $parametros['puntaje'] < 6)
                    return $handler->handle($request);
            }
            throw new Exception('Campos Invalidos');
        }

        public static function ValidarCodigoMesa($request, $handler){
            $parametros = $request->getParsedBody();
            $comentario = Comentario::obtenerComentarioCodigoMesa($parametros['codigoMesa']);
            if($comentario){
                throw new Exception('Esta mesa ya tiene un comentario asociado.');
            }
            return $handler->handle($request);
        }
    }

?>