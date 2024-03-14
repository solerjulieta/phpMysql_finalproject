<?php

namespace App\Modelos;

use App\DB\DBConexion;
use PDO;
use PDOException;

class Orden extends Modelo
{
    protected int $orden_id;
    protected int $orden_estado_fk;
    protected int $usuario_fk;
    protected string $fecha_pedido;
    protected int $total;

    protected OrdenEstado $estado;

    protected string $table = "orden";
    protected string $primaryKey = "orden_id";

    /** @var array|string[] La lista de propiedades que pueden cargarse dinámicamente desde un array generado desde la base de datos. */
    protected array $properties = ['orden_id', 'orden_estado_fk', 'usuario_fk', 'fecha_pedido', 'total'];

    /**
     * Crea una nueva orden en la base de datos.
     * Si falla el insert, se lanza un PDOException.
     * 
     * @param array $dato
     * @return void
     * @throws PDOException
     */
    public function crear(array $dato)
    {
        $db = DBConexion::getConexion();
        DBConexion::transaction(function() use($db, $dato){
            $query = "INSERT INTO orden (orden_estado_fk, usuario_fk, fecha_pedido, total)
            VALUES (:orden_estado_fk, :usuario_fk, :fecha_pedido, :total);";
            $stmt = $db->prepare($query);
            $stmt->execute([
                'orden_estado_fk' => $dato['orden_estado_fk'],
                'usuario_fk'      => $dato['usuario_fk'],
                'fecha_pedido'    => $dato['fecha_pedido'],
                'total'           => $dato['total'],
            ]);

            $ordenId = $db->lastInsertId();
            $productos = $dato['producto_fk'];
            $cantidades = $dato['cantidad'];
            $subtotales = $dato['subtotal'];
            $this->grabarOrdenTieneProductos($ordenId, $productos, $cantidades, $subtotales);
        });
    }

    /**
     * Inserta valores a tabla relacionada de la orden generada.
     * Debe recibir el id de la orden, y arrays de ids de los productos, cantidades y subtotales.
     * 
     * @param int $ordenId
     * @param array $productos
     * @param array $cantidades
     * @param array $subtotales
     * @return void
     */
    public function grabarOrdenTieneProductos(int $ordenId, array $productos, array $cantidades, array $subtotales)
    {
        $insertDatos = [];
        $insertValores = [];
        foreach($productos as $indice => $productoFk){
            $insertDatos[] = "(?, ?, ?, ?)";
            $insertValores[] = $productoFk;
            $insertValores[] = $ordenId;
            $insertValores[] = $cantidades[$indice];
            $insertValores[] = $subtotales[$indice];
        }
        $query = "INSERT INTO orden_tiene_productos (producto_fk, orden_fk, cantidad, subtotal)
                VALUES " . implode(', ', $insertDatos);
        DBConexion::executeQuery($query, $insertValores);
    }

    /**
     * Obtiene la orden del usuario indicado.
     * 
     * @param int $usuario_fk
     * @return Orden|null
     */
    public function obtenerOrdenes($usuario_fk)
    {
        $db = DBConexion::getConexion();
        $query = "SELECT * FROM orden o
                INNER JOIN orden_estado oe ON o.orden_estado_fk = oe.orden_estado_id
                WHERE usuario_fk = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$usuario_fk]);

        $salida = [];

        while($fila = $stmt->fetch(PDO::FETCH_ASSOC)){
            $obj = new Orden();
            $obj->loadProperties($fila);

            $estado = new OrdenEstado();
            $estado->loadProperties($fila);
            $obj->setEstado($estado);

            $salida[] = $obj;
        }
        return $salida;
    }

    /**
     * Actualiza el estado de la orden. 
     * 
     * @param array $dato
     * @return void
     * @throws PDOException
     */
    public function editar(array $dato): void 
    {
        $db = DBConexion::getConexion();
        DBConexion::transaction(function() use($db, $dato){
            $query = "UPDATE orden
                     SET orden_estado_fk = :orden_estado_fk
                     WHERE orden_id = :orden_id";
            
            $db->prepare($query)->execute([
                'orden_id' => $this->getOrdenId(),
                'orden_estado_fk' => $dato['orden_estado_fk'],
            ]);
        });
    }

    /**
     * Setters y Getters
    */

    /**
    * @return int|null
    */
    public function getOrdenId(): ?int
    {
        return $this->orden_id;
    }

    /**
    * @param int $orden_id
    */
    public function setOrdenId(): ?int
    {
        $this->orden_id = $orden_id;
    }

    /**
    * @return int|null
    */
    public function getOrdenEstadoFk(): int 
    {
        return $this->orden_estado_fk;
    }

    /**
    * @param int $orden_estado_fk
    */
    public function setOrdenEstadoFk(int $orden_estado_fk): void
    {
        $this->orden_estado_fk = $orden_estado_fk;
    }

    /**
    * @return int|null
    */
    public function getUsuarioFk(): ?int 
    {
        return $this->usuario_fk;
    }

    /**
    * @param int $usuario_fk
    */
    public function setUsuarioFk(): ?int
    {
        $this->usuario_fk = $usuario_fk;
    }

    /**
     * @return string|null
     */
    public function getFechaPedido(): ?string
    {
        return $this->fecha_pedido;
    }

    /**
     * @param string $fecha_pedido
     */
    public function setFechaPedido(): ?string
    {
        $this->fecha_pedido = $fecha_pedido;
    }

    /**
    * @return int|null
    */
    public function getTotal(): ?int 
    {
        return $this->total;
    }

    /**
    * @param int $total
    */
    public function setTotal(): ?int 
    {
        $this->total = $total;
    }

    /**
     * @return OrdenEstado
     */
    public function getEstado(): OrdenEstado 
    {
        return $this->estado;
    }

    /**
     * @param OrdenEstado $estado
     */
    public function setEstado(OrdenEstado $estado): void 
    {
        $this->estado = $estado;
    }
}