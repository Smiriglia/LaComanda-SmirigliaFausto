<?php

    require_once("./models/class_log_transaccion.php");

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
    }

?>