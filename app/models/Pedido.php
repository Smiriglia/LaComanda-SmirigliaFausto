<?php
require_once 'Producto.php';
class Pedido{
    public $id;
    public $codigoPedido;
    public $idMesa;
    public $idProducto;
    public $nombreCliente;
    public $sector;
    public $estado;
    public $importe;
    public $cantidad;
    public $fechaInicio;
    public $fechaCierre;
    
    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (codigoPedido, idMesa, idProducto, nombreCliente, sector, estado, importe, cantidad, fechaInicio, fechaCierre) VALUES (:codigoPedido, :idMesa, :idProducto, :nombreCliente, :sector, :estado, :importe, :cantidad, :fechaInicio, :fechaCierre)");
        $fecha = new DateTime(date('Y-m-d H:i:s'));
        $consulta->bindValue(':codigoPedido', $this->codigoPedido, PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':idProducto', $this->idProducto, PDO::PARAM_INT);
        $consulta->bindValue(':nombreCliente', $this->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':estado', 'pendiente', PDO::PARAM_STR);
        $consulta->bindValue(':importe', $this->importe, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);     
        $consulta->bindValue(':fechaInicio', date_format($fecha, 'Y-m-d H:i:s'), PDO::PARAM_STR);
        $consulta->bindValue(':fechaCierre', null, PDO::PARAM_STR);
        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigoPedido, idMesa, idProducto, nombreCliente, sector, estado, importe, cantidad, fechaInicio, fechaCierre FROM pedidos");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerTodosFinalizados($estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigoPedido, idMesa, idProducto, nombreCliente, sector, estado, importe, cantidad, fechaInicio, fechaCierre FROM pedidos WHERE estado = :estado");
        $consulta->bindValue(':estado', $estado, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedido($codigoPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigoPedido, idMesa, idProducto, nombreCliente, sector, estado, importe, cantidad, fechaInicio, fechaCierre FROM pedidos WHERE codigoPedido = :codigoPedido");
        $consulta->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedidosPorMesa($idMesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigoPedido, idMesa, idProducto, nombreCliente, sector, estado, importe, cantidad, fechaInicio, fechaCierre FROM pedidos WHERE idMesa = :idMesa");
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedidoParaCobrar($idMesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigoPedido, idMesa, idProducto, nombreCliente, sector, estado, importe, cantidad, fechaInicio, fechaCierre FROM pedidos WHERE idMesa = :idMesa");
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedidoIndividual($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigoPedido, idMesa, idProducto, nombreCliente, sector, estado, importe, cantidad, fechaInicio, fechaCierre FROM pedidos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchObject('Pedido');
    }

    public static function modificarPedido($pedido, $idNuevoProducto = false)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET idMesa = :idMesa, idProducto = :idProducto, nombreCliente = :nombreCliente, sector = :sector, importe = :importe, cantidad = :cantidad, estado = :estado WHERE id = :id");
        $consulta->bindValue(':id', $pedido->id, PDO::PARAM_INT);
        $consulta->bindValue(':idMesa', $pedido->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':idProducto', $pedido->idProducto, PDO::PARAM_INT);
        $consulta->bindValue(':nombreCliente', $pedido->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $pedido->sector, PDO::PARAM_STR);
        $consulta->bindValue(':importe', $pedido->importe, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $pedido->cantidad, PDO::PARAM_INT);
        if($idNuevoProducto){
            $nuevoProducto = Producto::obtenerProducto($idNuevoProducto);
            $consulta->bindValue(':idProducto', $pedido->idProducto, PDO::PARAM_INT);
            $consulta->bindValue(':importe', $nuevoProducto->precio * $pedido->cantidad , PDO::PARAM_INT);
        }
        $consulta->bindValue(':estado', $pedido->estado, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function borrarPedido($pedido) {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET estado = :estado, fechaCierre = :fechaCierre WHERE id = :id");
        $fecha = new DateTime(date('Y-m-d'));
        $consulta->bindValue(':id', $pedido->id, PDO::PARAM_INT);
        $consulta->bindValue(':estado', 'cancelado', PDO::PARAM_STR);
        $consulta->bindValue(':fechaCierre', date_format($fecha, 'Y-m-d'), PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function obtenerTodosPorSector($sector){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigoPedido, idMesa, idProducto, nombreCliente, sector, estado, importe, cantidad, fechaInicio, fechaCierre FROM pedidos WHERE sector = :sector AND estado = :estado");
        $consulta->bindValue(':sector', $sector, PDO::PARAM_STR);
        $consulta->bindValue(':estado', 'pendiente', PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
    //de pendiente (mozo) a -> en preparacion (los distintos roles)
    public static function updatePedidoPendiente($pedido) {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET estado = :estado WHERE id = :id");        
        $consulta->bindValue(':id', $pedido->id, PDO::PARAM_INT);
        $consulta->bindValue(':estado', 'en preparacion', PDO::PARAM_STR);
        $consulta->execute();
    }
    //de en preparacion (los distintos roles) a -> preparado 
    public static function updatePedidoEnPreparacion($pedido) {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET estado = :estado, fechaCierre = :fechaCierre WHERE id = :id");
        $fecha = new DateTime($pedido->fechaInicio);
        $intervalo = random_int(5,45) + 5;
        $pedido->fechaCierre = $fecha->add(new DateInterval('PT'.$intervalo.'M10S')); 
        $consulta->bindValue(':id', $pedido->id, PDO::PARAM_INT);
        $consulta->bindValue(':estado', 'preparado', PDO::PARAM_STR);
        $consulta->bindValue(':fechaCierre', date_format($pedido->fechaCierre, 'Y-m-d H:i:s'), PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function LlevarPedido($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE pedidos SET estado = :estado WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':estado', 'completado', PDO::PARAM_STR);
        $consulta->execute();
    }
    
}