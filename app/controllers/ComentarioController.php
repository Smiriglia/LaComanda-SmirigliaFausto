<?php
require_once './models/Comentario.php';
require_once './interfaces/IApiUsable.php';
class ComentarioController extends Comentario implements IApiUsable{
    
    public function TraerUno($request, $response, $args){
        $parametros = $request->getQueryParams();
        $id = $parametros['id'];
        $prd = Comentario::obtenerComentario($id);
        $payload = json_encode($prd);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args){
        $lista = Comentario::obtenerTodos();
        $payload = json_encode(array("listaComentario" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerMejores($request, $response, $args){
        $lista = Comentario::obtenerTodos();
        $lista_mejores = [];
        $max_puntaje = 0; // el puntaje esta validado a ser entre (1-5)

        foreach ($lista as $comentario) {
            if ($comentario->puntaje > $max_puntaje) {
                $max_puntaje = $comentario->puntaje;
            }
        }
        foreach ($lista as $comentario)
        {
            if ($comentario->puntaje === $max_puntaje)
                $lista_mejores[] = $comentario;    
        }

        $payload = json_encode(array("listaComentario" => $lista_mejores));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function CargarUno($request, $response, $args){
        $parametros = $request->getParsedBody();

        $codigoMesa = $parametros['codigoMesa'];
        $puntaje = $parametros['puntaje'];
        $comentario = $parametros['comentario'];
        $prd = new Comentario();
        $prd->codigoMesa = $codigoMesa;
        $prd->puntaje = $puntaje;
        $prd->comentario = $comentario;
        $prd->crearComentario();

        $payload = json_encode(array("mensaje" => "Comentario creado con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args){
        $parametros = $request->getParsedBody();
        Comentario::borrarComentario($parametros['id']);
        $payload = json_encode(array("mensaje" => "Comentario borrado con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args){
        $parametros = $request->getParsedBody();
        
        $comentario = Comentario::obtenerComentario($parametros['id']);

        if(isset($parametros['codigoMesa'])){
            $comentario->codigoMesa = $parametros['codigoMesa'];
        }
        if(isset($parametros['puntaje'])){
            $comentario->puntaje = $parametros['puntaje'];
        }
        if(isset($parametros['comentario'])){
            $comentario->comentario = $parametros['comentario'];
        }
    
        Comentario::modificarComentario($comentario);
        $payload = json_encode(array("mensaje" => "Comentario modificado con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}