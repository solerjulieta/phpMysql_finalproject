<?php

namespace App\Modelos;

class UsuarioRol extends Modelo
{
   protected int $rol_id;
   protected string $rol_nombre;
   
   protected string $table = "roles";
   protected string $primaryKey = "rol_id";
   protected array $properties = ['rol_id', 'rol_nombre'];

   /**
    * Setters y Getters
    */

   /**
    * @return int|null
    */
   public function getRolId(): int 
   {
      return $this->rol_id;
   }

   /**
    * @return string|null
    */
   public function getNombre(): string 
   {
      return $this->rol_nombre;
   }

   /**
    * @param int $noticia_id
    */
   public function setRolId(int $rol_id): void
   {
      $this->rol_id = $rol_id;
   }

   /**
    * @param string $noticia_id
    */
   public function setNombre(string $rol_nombre): void
   {
      $this->rol_nombre = $rol_nombre;
   }
}