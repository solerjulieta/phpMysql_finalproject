<?php

namespace App\Modelos;

use App\Modelos\DBConexion;
use PDO;

class ProductoCategoria extends Modelo
{
    protected int $categoria_id;
    protected string $categoria_nombre;

    protected string $table = "categoria";
    protected string $primaryKey = "categoria_id";

    /** @var array|string[] Lista de propiedades que pueden cargarse dinámicamente desde un array generado desde la base de datos. */
    protected array $properties = ['categoria_id', 'categoria_nombre'];

    /**
     * Getters & Setters
     */
    
    /**
     * @return int
     */
    public function getCategoriaId(): int 
    {
        return $this->categoria_id;
    }

    /**
     * @return string
     */
    public function getNombre(): string 
    {
        return $this->categoria_nombre;
    }

    /**
     * @param int $categoria_id
     */
    public function setCategoriaId(int $categoria_id): void 
    {
        $this->categoria_id = $categoria_id;
    }

    /**
     * @param string $nombre
     */
    public function setNombre(string $categoria_nombre): void 
    {
        $this->categoria_nombre = $categoria_nombre;
    }
}


