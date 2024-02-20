<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $email = $parametros['email'];
        $clave = $parametros['clave'];
        $rol = $parametros['rol'];
        $usr = new Usuario();
        $usr->nombre = $nombre;
        $usr->email = $email;
        $usr->clave = $clave;
        $usr->rol = $rol;
        $usr->crearUsuario();

        $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $parametros = $request->getQueryParams();
        $id = $parametros['id'];
        $usuario = Usuario::obtenerUsuario($id);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::obtenerTodos();
        $payload = json_encode(array("listaUsuario" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $usuario = Usuario::obtenerUsuario($parametros['id']);
        if(isset($parametros['nombre'])){
            $usuario->nombre = $parametros['nombre'];
        }
        if(isset($parametros['clave'])){
            $usuario->clave = $parametros['clave'];
        }
        if(isset($parametros['email'])){
            $usuario->email = $parametros['email'];
        }
        if(isset($parametros['rol'])){
            $usuario->rol = $parametros['rol'];
        }
        if(isset($parametros['estado'])){
            $usuario->estado = $parametros['estado'];
        }
        Usuario::modificarUsuario($usuario);
        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $usuario = Usuario::obtenerUsuario($parametros['id']);
        Usuario::borrarUsuario($usuario);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    
    public function DescargarPDF($request, $response, $args)
    {
        Usuario::ExportarPDF();
        $payload = json_encode(array("mensaje" => "Usuarios exportados a pdf con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ExportarLogo($request, $response, $args)
    {
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Logo Empresa', 0, 1, 'C');
        $pdf->Ln(10);
        $imagen = './logo.jpg';
        list($ancho, $alto) = getimagesize($imagen);
        $ancho_pdf = 100;
        $alto_pdf = $alto * ($ancho_pdf / $ancho);

        $pdf->Image($imagen, 10, 30, $ancho_pdf);

        $pdf->Output('./logo.pdf', 'F');

        $payload = json_encode(array("mensaje" => "logo exportado correctamente"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
}
