<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';
class ProductoController extends Producto implements IApiUsable{
    
    public function TraerUno($request, $response, $args){
        $parametros = $request->getQueryParams();
        $id = $parametros['id'];
        $prd = Producto::obtenerProducto($id);
        $payload = json_encode($prd);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args){
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("listaProducto" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function CargarUno($request, $response, $args){
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $tipo = $parametros['tipo'];
        $precio = $parametros['precio'];
        $tiempo = $parametros['tiempoPreparacion'];
        $prd = new Producto();
        $prd->nombre = $nombre;
        $prd->tipo = $tipo;
        $prd->precio = $precio;
        $prd->tiempoPreparacion = $tiempo;
        $prd->crearProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function BorrarUno($request, $response, $args){
        $parametros = $request->getParsedBody();
        Producto::borrarProducto($parametros['id']);
        $payload = json_encode(array("mensaje" => "Producto borrado con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function ModificarUno($request, $response, $args){
        $parametros = $request->getParsedBody();
        $producto = Producto::obtenerProducto($parametros['id']);
        if(isset($parametros['nombre'])){
            $producto->nombre = $parametros['nombre'];
        }
        if(isset($parametros['tipo'])){
            $producto->tipo = $parametros['tipo'];
        }
        if(isset($parametros['precio'])){
            $producto->precio = $parametros['precio'];
        }
        if(isset($parametros['tiempoPreparacion'])){
            $producto->tiempoPreparacion = $parametros['tiempoPreparacion'];
        }
        Producto::modificarProducto($producto);
        $payload = json_encode(array("mensaje" => "Producto modificado con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function CargarCSV($request, $response, $args) {
        $parametros = $request->getUploadedFiles();
        $archivo = fopen($parametros['archivo']->getFilePath(), 'r');
        $encabezado = fgetcsv($archivo);
        while(($datos = fgetcsv($archivo)) !== false){
            $producto = new Producto();
            $producto->id = $datos[0];
            $producto->nombre = $datos[1];
            $producto->tipo = $datos[2];
            $producto->precio = $datos[3];
            $producto->tiempoPreparacion = $datos[4];
            $producto->crearProducto();
        }
        fclose($archivo);
        $payload = json_encode(array("mensaje" => "Lista de productos cargada exitosamente"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

}