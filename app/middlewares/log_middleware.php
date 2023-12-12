<?php
    require_once './controllers/log_transacciones_controller.php';
    require_once './middlewares/AutentificadorJWT.php';
    class LogMiddleware
    {
        public static function LogTransaccion($request, $handler)
        {
                $coockies = $request->getCookieParams();
                if (isset($coockies['JWT']))
                {
                    $token = $coockies['JWT'];
                    AutentificadorJWT::VerificarToken($token);
                    $datos = AutentificadorJWT::ObtenerData($token);
                    $idUsuario = $datos->id;
                }
                else
                    $idUsuario = -1;

            $response = $handler->handle($request);

            $code = $response->getStatusCode();
            $accion = $request->getUri()->getPath();
            LogTransaccionesController::InsertarLogTransaccion($idUsuario, $accion, $code);
        
            return $response;
        }
    }

?>