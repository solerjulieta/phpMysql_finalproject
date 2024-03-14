<?php

namespace App\Modelos;

use App\DB\DBConexion;
use App\Modelos\Productos;
use PDO;
use PDOException;

class DetalleItemCarrito extends Modelo
{
    private Productos $producto;
    protected int $producto_fk;
    protected int $carrito_fk;
    protected int $cantidad;
    protected int $subtotal;

    /**
     * Crea el detalle del carrito en la base de datos.
     * Si falla el insert, se lanza una PDOException.
     * 
     * @param array $dato
     * @return void
     * @throws PDOException
     */
    public function crear(array $dato)
    {
        $db = DBConexion::getConexion();
        DBConexion::transaction(function() use($db, $dato){
            $query = "INSERT INTO detalle_item_carrito (producto_fk, carrito_fk, cantidad, subtotal)
                     VALUES (:producto_fk, :carrito_fk, :cantidad, :subtotal);";
            $stmt = $db->prepare($query);
            $stmt->execute([
                'producto_fk' => $dato['producto_fk'],
                'carrito_fk'  => $dato['carrito_fk'],
                'cantidad'    => $dato['cantidad'],
                'subtotal'    => $dato['subtotal'],
            ]);
        });
    }

    /**
     * Obtiene el item de un carrito indicado.
     * 
     * @param int $producto_fk
     * @param int $carrito_fk
     * @return DetalleItemCarrito|null
     */
    public function obtenerItem($producto_fk, $carrito_fk)
    {
        $db = DBConexion::getConexion();
        $query = "SELECT * FROM detalle_item_carrito
                WHERE producto_fk = ?
                AND carrito_fk = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$producto_fk, $carrito_fk]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
          
        $itemCarrito = $stmt->fetch();
          
        return $itemCarrito ? $itemCarrito : null;
    }

    /**
     * Obtiene los items de un carrito indicado.
     * 
     * @param int $carrito_fk
     * @return DetalleItemCarrito|null
     */
    public function obtenerCarritoItems($carrito_fk)
    {
        $db = DBConexion::getConexion();
        $query = "SELECT * FROM detalle_item_carrito
                WHERE carrito_fk = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$carrito_fk]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
                  
        $carritoItems = $stmt->fetch();
                  
        return $carritoItems ? $carritoItems : null;
    }

    /**
     * Obtiene el total de un carrito indicado.
     * 
     * @param int $carrito_fk
     * @return $total|null
     */
    public function obtenerTotal($carrito_fk)
    {
        $db = DBConexion::getConexion();
        $query = "SELECT SUM(subtotal) FROM detalle_item_carrito
                WHERE carrito_fk = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$carrito_fk]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
                  
        $total = $stmt->fetch();
                  
        return $total ? $total : null;
    }

    /**
     * Actualiza la cantidad y subtotal del carrito.
     * 
     * @param array $dato
     * @return void
     * @throws PDOException
     */
    public function actualizarCantidadySubtotal(array $dato): void
    {
        $db = DBConexion::getConexion();
        DBConexion::transaction(function() use($db, $dato){
            $query = "UPDATE detalle_item_carrito
                SET cantidad = :cantidad,
                    subtotal = :subtotal
                WHERE producto_fk = :producto_fk
                AND carrito_fk = :carrito_fk";

            $db->prepare($query)->execute([
                'producto_fk' => $this->getProductoFk(),
                'carrito_fk'  => $this->getCarritoFk(),
                'cantidad'    => $dato['cantidad'],
                'subtotal'    => $dato['subtotal'],
            ]);
        });
    }

    /**
     * Elimina un item.
     * 
     * @return void
     * @throws PDOException
     */
    public function eliminarItem()
    {
        $db = DBConexion::getConexion();
        DBConexion::transaction(function() use($db){
            $query = "DELETE FROM detalle_item_carrito
                WHERE producto_fk = ?
                AND carrito_fk = ?";
            $db->prepare($query)->execute([$this->getProductoFk(), $this->getCarritoFk()]);
        });
    }

    /**
     * Setters y Getters
     */

    /**
     * @return Usuario
     */    
    public function getProducto(): Productos 
    {
        return $this->producto;
    }

    /**
     * @param int $cantidad
     */
    public function incrementarCantidad(int $cantidad = 1)
    {
        $this->cantidad += $cantidad;
    }

    /**
     * @param int $cantidad
     */
    public function decrementarCantidad(int $cantidad = 1)
    {
        $this->cantidad -= $cantidad;
    }

    /**
     * @param int $subtotal
     */
    public function actualizarSubtotal(int $precio, int $cantidad)
    {
        $this->subtotal = $precio * $cantidad;
    }

    /**
     * @param int $subtotal
     */    
    public function actualizarSubtotalResta(int $subtotal, int $precio)
    {
        $this->subtotal = $subtotal - $precio;
    }

    /**
     * @return int|null
     */
    public function getProductoFk(): int 
    {
        return $this->producto_fk;
    }

    /**
     * @return int|null
     */
    public function getCarritoFk(): int 
    {
        return $this->carrito_fk;
    }

    /**
     * @return int|null
     */
    public function getCantidad(): int
    {
        return $this->cantidad;
    }

    /**
     * @return int|null
     */
    public function getSubtotal(): int
    {
        return $this->subtotal;
    }

    /**
     * @param int $producto_fk
     */
    public function setProductoFk(int $producto_fk): void
    {
        $this->producto_fk = $producto_fk;
    }
}