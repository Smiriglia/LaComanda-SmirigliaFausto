<?php
require_once ("./utilidades/class_pdf.php");
class Usuario
{
    public $id;
    public $nombre;
    public $email;
    public $clave;
    public $rol;
    public $estado;
    public $fechaInicio;
    public $fechaFinalizacion;

    public function crearUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (nombre, email, clave, rol, estado, fechaInicio, fechaFinalizacion) VALUES (:nombre, :email, :clave, :rol, :estado, :fechaInicio, :fechaFinalizacion)");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $fecha = new DateTime(date('Y-m-d'));
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':rol', $this->rol, PDO::PARAM_STR);
        $consulta->bindValue(':estado', 'activo', PDO::PARAM_STR);
        $consulta->bindValue(':fechaInicio', date_format($fecha, 'Y-m-d'), PDO::PARAM_STR);
        $consulta->bindValue(':fechaFinalizacion', null, PDO::PARAM_STR);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, email, clave, rol, estado, fechaInicio FROM usuarios");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUsuario($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, email, clave, rol, estado, fechaInicio, fechaFinalizacion FROM usuarios WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();
        
        return $consulta->fetchObject('Usuario');
    }

    public static function obtenerUsuarioEmail($email)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, email, clave, rol, estado, fechaInicio, fechaFinalizacion FROM usuarios WHERE email = :email");
        $consulta->bindValue(':email', $email, PDO::PARAM_STR);
        $consulta->execute();
        
        return $consulta->fetchObject('Usuario');
    }

    public static function modificarUsuario($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET nombre = :nombre, clave = :clave, email = :email, rol = :rol, estado = :estado WHERE id = :id");
        $consulta->bindValue(':id', $usuario->id, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $usuario->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':email', $usuario->email, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $usuario->clave, PDO::PARAM_STR);
        $consulta->bindValue(':rol', $usuario->rol, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $usuario->estado, PDO::PARAM_STR);
        
        $consulta->execute();
    }

    public static function borrarUsuario($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET fechaFinalizacion = :fechaFinalizacion, estado = :estado WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $usuario->id, PDO::PARAM_INT);
        $consulta->bindValue(':estado', 'inactivo', PDO::PARAM_STR);
        $consulta->bindValue(':fechaFinalizacion', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }

    public static function ExportarPDF($path = "./usuarios.pdf")
    {
        $pdf = new PDF();
        $pdf->AddPage();
        
        $usuarios = Usuario::obtenerTodos();

        // Agregar objetos al PDF
        foreach ($usuarios as $usuario) {
            $pdf->ChapterTitle($usuario->nombre);
            $pdf->ChapterBody($usuario->email . " " .  $usuario->rol . " " . $usuario->estado);
            $pdf->Ln();
        }

        $pdf->Output($path, 'F');
    }

    public static function ObtenerSectores()
    {
        $sectores = [];
        $usuarios = Usuario::obtenerTodos();

        foreach ($usuarios as $usuario)
        {
            $sectores[$usuario->rol] = 0;
        }

        return $sectores;
    }
}