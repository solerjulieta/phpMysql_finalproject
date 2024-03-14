<?php

namespace App\Validacion;

/**
 * Valida los datos ingresados del producto.
 */
class ValidarProducto
{
   /** @var array Los datos a validar */  
   protected $dato = [];
   /** @var array Los errores encontrados */
   protected $errores = [];

   /**
      * @param array $dato Los datos a validar.
   */

   public function __construct(array $dato)
   {
         $this->dato = $dato;
         $this->validar();
   }

   public function hayError(): bool
   {
      return !empty($this->errores);
   }

   /**
      * @return array
   */

   public function getError(): array
   {
      return $this->errores;
   }

   protected function validar()
   {
      if(empty($this->dato['categoria_fk'])){
         $this->errores['categoria_fk'] = 'Debés seleccionar la categoría del producto.';
      } 

      if(empty($this->dato['nombre'])){
         $this->errores['nombre'] = 'Debés ingresar el nombre del producto.';
      } else if(strlen($this->dato['nombre']) < 6){
         $this->errores['nombre'] = 'El nombre del producto debe tener al menos 6 caracteres.';
      }

      if(empty($this->dato['descripcion'])){
         $this->errores['descripcion'] = 'Debés ingresar la descripción del producto.';
      } 

      if(empty($this->dato['precio'])){
         $this->errores['precio'] = 'Debés ingresar el precio del producto.';
      }
   }
}