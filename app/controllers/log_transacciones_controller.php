<?php

    require_once("./models/class_log_transaccion.php");
    require_once("./models/Usuario.php");

    class LogTransaccionesController {

        public function GetTransacciones($request, $response, $args)
        {
            $transacciones = LogTransaccion::TraerTodo();

            $payload = json_encode(["transacciones" => $transacciones]);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public static function InsertarLogTransaccion($idUsuario, $accion, $code)
        {
            $logTransaccion = new LogTransaccion();
            $logTransaccion->idUsuario = $idUsuario;
            $logTransaccion->code = $code;
            $logTransaccion->accion = $accion;

            $logTransaccion->Insertar();
        }

        public function CalcularCantidadOperaciones($request, $response, $args)
        {
            $sectores = Usuario::ObtenerSectores();
            $transacciones = LogTransaccion::TraerTodo();

            foreach ($transacciones as $transaccion)
            {
                $usuario = Usuario::obtenerUsuario($transaccion->idUsuario);
                $sectores[$usuario->rol] += 1;
            }

            $payload = json_encode(["cantidadOperaciones" => $sectores]);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function CalcularCantidadOperacionesUsuarios($request, $response, $args)
        {
            $sectores = [];
            $transacciones = LogTransaccion::TraerTodo();

            foreach ($transacciones as $transaccion)
            {
                $usuario = Usuario::obtenerUsuario($transaccion->idUsuario);
                if (isset($sectores[$usuario->rol][$usuario->id]))
                    $sectores[$usuario->rol][strval($usuario->id)] += 1;
                else
                    $sectores[$usuario->rol][strval($usuario->id)] = 1;
            }

            $payload = json_encode(["cantidadOperaciones" => $sectores]);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

    }

?>