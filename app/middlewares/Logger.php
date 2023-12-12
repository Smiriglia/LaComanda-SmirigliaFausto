<?php
    require_once './models/Usuario.php';
    class Logger
    {
        public static function LogOperacion($request, $response, $next)
        {
            $retorno = $next($request, $response);
            return $retorno;
        }

        public static function LimpiarCoockieUsuario($request, $handler){
            setcookie('JWT', '', time()-3600, '/', 'localhost', false, true);
            return $handler->handle($request);
        }

        public static function Loguear($request, $response, $args){
            $parametros = $request->getParsedBody();
            $email = $parametros['email'];
            $clave = $parametros['clave'];
            $usuario = Usuario::obtenerUsuarioEmail($email);
            if($usuario !== null && password_verify($clave, $usuario->clave)){
                $token = AutentificadorJWT::CrearToken(array('id' => $usuario->id, 'nombre' => $usuario->nombre, 'email' => $usuario->email, 'rol' => $usuario->rol, 'estado' => $usuario->estado));
                setcookie('JWT', $token, time()+60*60*24*30, '/', 'localhost', false, true);
                $payload = json_encode(array('mensaje'=>'Logueo Exitoso - Usted es: [ '.$usuario->rol.' ]'));
            }
            else{
                $payload = json_encode(array('mensaje'=>'Datos Invalidos'));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public static function Salir($request, $response, $args){
            $payload = json_encode(array('mensaje'=>'Sesion Cerrada'));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public static function ValidarSesionIniciada($request, $handler){
            $cookie = $request->getCookieParams();
            if(isset($cookie['JWT'])){
                $token = $cookie['JWT'];
                $datos = AutentificadorJWT::ObtenerData($token);
                if($datos->estado == 'activo'){
                    return $handler->handle($request);
                }
                else{
                    throw new Exception('Usted no es un usuario activo');
                }
            }
            throw new Exception('Debe haber iniciado sesion');
        }
    }

?>
