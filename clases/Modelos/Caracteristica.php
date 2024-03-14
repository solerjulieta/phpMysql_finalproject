<?php

namespace App\Modelos;

class Caracteristica extends Modelo
{
    protected int $caracteristicas_id;
    protected string $cualidad;

    protected string $table = 'caracteristicas';
    protected string $primaryKey = 'caracteristicas_id';
    
    /** @var array|string[] La lista de propiedades que pueden cargarse dinámicamente desde un array generado desde la base de datos. */
    protected array $properties = ['caracteristicas_id', 'cualidad'];

    /**
     * @return int
     */
    public function getCaracteristicaId(): int 
    {
        return $this->caracteristicas_id;
    }

    /**
     * @return string
     */
    public function getCualidad(): string 
    {
        return $this->cualidad;
    }

    /**
     * @param int $caracteristicas_id
     */
    public function setCaracteristicaId(int $caracteristicas_id): void 
    {
        $this->caracteristicas_id = $caracteristicas_id;
    }

    /**
     * @param string $cualidad
     */
    public function setCualidad(string $cualidad): void 
    {
        $this->cualidad = $cualidad;
    }
}