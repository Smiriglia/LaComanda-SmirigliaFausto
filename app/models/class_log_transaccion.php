<?php
    class LogTransaccion
    {
        public $nroTransaccion;
        public $fecha;
        public $idUsuario;
        public $code;
        public $accion;

        public function Insertar()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();

            $fecha = new DateTime();
            $fechaStr = $fecha->format('d/m/Y H:i:s');
            $this->fecha = $fechaStr;

            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO logTransacciones (fecha, idUsuario, accion, code) VALUES (:fecha, :idUsuario, :accion, :code)");


            $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
            $consulta->bindValue(':idUsuario', $this->idUsuario, PDO::PARAM_STR);
            $consulta->bindValue(':code', $this->code, PDO::PARAM_INT);
            $consulta->bindValue(':accion', $this->accion, PDO::PARAM_INT);
            $consulta->execute();

            $resultado =  $objAccesoDatos->obtenerUltimoId();

            return $resultado;
        }
        

        public static function TraerTodo()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM logTransacciones");
            $consulta->execute();

            return $consulta->fetchAll(PDO::FETCH_CLASS, 'logTransaccion');
        }

        public static function TraerUnLog($nroTransaccion)
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM logTransacciones WHERE nroTransaccion = :nroTransaccion");
            
            $consulta->bindValue(':nroTransaccion', $nroTransaccion, PDO::PARAM_STR);
            $consulta->execute();
            
            $resultado = $consulta->fetchObject('logTransaccion');
            if ($resultado === false)
                return null;

            return $resultado;
        }

        public static function ExportarCSV($path = "./datos/logTransacciones.csv")
        {
            $logTransacciones = LogTransaccion::TraerTodo();

            $archivo = fopen($path, 'w');
            
            $encabezado = ['nroTransaccion', 'fecha', 'idUsuario', 'code', 'accion'];
            fputcsv($archivo, $encabezado);
            
            foreach ($logTransacciones as $logTransaccion) {
                $datos = [$logTransaccion->nroTransaccion, $logTransaccion->fecha, $logTransaccion->idUsuario, $logTransaccion->code, $logTransaccion->accion];
                fputcsv($archivo, $datos);
            }
            fclose($archivo);
        }
    }
?>