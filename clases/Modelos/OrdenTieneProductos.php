<?php

namespace App\Modelos;

use App\DB\DBConexion;
use PDO;

class OrdenTieneProductos extends Modelo 
{
    protected int $producto_fk;
    protected int $orden_fk;
    protected int $cantidad;
    protected int $subtotal;

    /**
     * Obtiene los items de la orden indicada.
     * 
     * @param int $orden_fk
     * @return OrdenTieneProductos|null
     */
    public function obtenerItemsOrden($orden_fk)
    {
        $db = DBConexion::getConexion();
        $query = "SELECT p.nombre, otp.* FROM orden_tiene_productos otp
                INNER JOIN productos p ON otp.producto_fk = p.producto_id
                WHERE otp.orden_fk = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$orden_fk]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
  
        return $stmt->fetchAll();
    }

    /**
     * Obtiene un producto específico de las órdenes generadas.
     * 
     * @param int $producto_fk
     * @return OrdenTieneProductos|null
     */
    public function obtenerProductoOrdenes($producto_fk)
    {
        $db = DBConexion::getConexion();
        $query = "SELECT * FROM orden_tiene_productos
                WHERE producto_fk = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$producto_fk]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
  
        return $stmt->fetchAll();        
    }

    /**
     * Setters y Getters
     */

    /**
     * @return int
     */
    public function getProductoFk(): int 
    {
        return $this->producto_fk;
    }

    /**
     * @param int $producto_fk
     */
    public function setProductoFk(int $producto_fk): void 
    {
        $this->producto_fk = $producto_fk;
    }

    /**
     * @return int
     */
    public function getOrdenFk(): int 
    {
        return $this->orden_fk;
    }

    /**
     * @param int $orden_fk
     */
    public function setOrdenFk(int $orden_fk): void 
    {
        $this->orden_fk = $orden_fk;
    }

    /**
     * @return int
     */
    public function getCantidad(): int 
    {
        return $this->cantidad;
    }

    /**
     * @param int $cantidad
     */
    public function setCantidad(int $cantidad): void 
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return int
     */
    public function getSubtotal(): int 
    {
        return $this->subtotal;
    }

    /**
     * @param int $subtotal
     */
    public function setSubtotal(int $subtotal): void 
    {
        $this->subtotal = $subtotal;
    }

    /**
     * @return string
     */
    public function getNombre(): ?string 
    {
        return $this->nombre;
    }
}