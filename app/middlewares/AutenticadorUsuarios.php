<?php
    class AutenticadorUsuario{

        public static function VerificarUsuario($request, $handler){
            $cookies = $request->getCookieParams();
            $token = $cookies['JWT'];
            AutentificadorJWT::VerificarToken($token);
            $datos = AutentificadorJWT::ObtenerData($token);
            if(self::ValidarRolUsuario($datos->rol)){
                return $handler->handle($request);
            }
            else{
                throw new Exception('No autorizado');
            }
        }

        public static function ValidarPermisosDeRol($request, $handler, $rol = false){
            $cookies = $request->getCookieParams();
            $token = $cookies['JWT'];
            AutentificadorJWT::VerificarToken($token);
            $datos = AutentificadorJWT::ObtenerData($token);
            if((!$rol && $datos->rol == 'socio') || $rol && $datos->rol == $rol || $datos->rol == 'socio'){
                return $handler->handle($request);
            }
            throw new Exception('Acceso denegado');
        }

        public static function ValidarPermisosDeRolDoble($request, $handler, $rol1 = false, $rol2 = false){
            $cookies = $request->getCookieParams();
            $token = $cookies['JWT'];
            AutentificadorJWT::VerificarToken($token);
            $datos = AutentificadorJWT::ObtenerData($token);
            if((!$rol1 && $datos->rol == 'socio') || ($rol1 && $datos->rol == $rol1) || ($rol2 && $datos->rol == $rol2) || ($datos->rol == 'socio' || $datos->rol == 'mozo')){
                return $handler->handle($request);
            }
            throw new Exception('Acceso denegado');
        }
        
        public static function ValidarCampos($request, $handler){
            $parametros = $request->getParsedBody();
            if(isset($parametros['nombre']) || isset($parametros['email']) || isset($parametros['clave']) || isset($parametros['rol']) || isset($parametros['estado'])){
                return $handler->handle($request);
            }
            throw new Exception('Campos Invalidos');
        }

        public static function ValidarRolUsuario($rol){
            if($rol !== null){
                if(empty($rol) || $rol != 'socio' && $rol != 'bartender' && $rol != 'cocinero' && $rol != 'mozo' && $rol != 'candybar'){
                    return false;
                }
            }
            return true;
        }


        
    }
?>