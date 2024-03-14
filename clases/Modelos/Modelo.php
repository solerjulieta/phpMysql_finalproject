<?php

namespace App\Modelos;

use App\DB\DBConexion;
use PDO;

/**
 * Funcionalidad base de los Modelos en la aplicación.
 */
class Modelo
{
    /** @var string La tabla a la que el modelo hace referencia. */
    protected string $table = '';
    
    /** @var string El nombre que es la PK en la tabla. */
    protected string $primaryKey = '';

    /**
     * @var array|string[] Lista de propiedades que pueden cargarse dinámicamente desde un array
     * generado desde la base de datos.
     */
    protected array $properties = [];

    /**
     * @param array $data
     * @return void
     */
    public function loadProperties(array $data) 
    {
        foreach($data as $key => $value){
            if(in_array($key, $this->properties)){
                $this->{$key} = $value;
            }
        }
    }

    /**
     * @return array|static[]
     */
    public function todoContenido(): array
    {
        $db = DBConexion::getConexion();
        $query = "SELECT * FROM {$this->table}";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, static::class);

        return $stmt->fetchAll();
    }

    /**
     * Obtiene el modelo por su PK.
     * Si no existe, retorna null.
     * 
     * @param int $pk
     * @return $static|null
     */
    public function traerPorId(int $pk): ?static
    {
        $db = DBConexion::getConexion();
        $query = "SELECT * FROM {$this->table} 
                  WHERE {$this->primaryKey} = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$pk]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, static::class);
        $obj = $stmt->fetch();

        return $obj ? $obj : null;
    }
}