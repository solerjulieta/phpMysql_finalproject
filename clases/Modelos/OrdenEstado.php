<?php

namespace App\Modelos;

use App\DB\DBConexion;
use PDO;

class OrdenEstado extends Modelo
{
    protected int $orden_estado_id;
    protected string $estado_nombre;

    protected string $table = "orden_estado";
    protected string $primarKey = "orden_estado_id";

    /** @var array|string[] La lista de propiedades que pueden cargarse dinámicamente desde un array generado desde la base de datos. */
    protected array $properties = ['orden_estado_id', 'estado_nombre'];

    /**
     * Setters y Getters
    */

    /**
     * @return int
     */
    public function getOrdenEstadoId(): int 
    {
        return $this->orden_estado_id;
    }

    /**
     * @param int $orden_estado_id
     */
    public function setOrdenEstadoId(int $orden_estado_id): void 
    {
        $this->orden_estado_id = $orden_estado_id;
    }

    /**
     * @return string
     */
    public function getNombreEstado(): string 
    {
        return $this->estado_nombre;
    }

    /**
     * @param string $estado_nombre
     */
    public function setNombreEstado(string $estado_nombre): void 
    {
        $this->estado_nombre = $estado_nombre;
    }
}