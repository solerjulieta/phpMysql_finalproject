<?php

namespace App\Modelos;

use App\DB\DBConexion;
use App\Modelos\Productos;
use App\Modelos\Usuario;
use App\Modelos\DetalleItemCarrito;
use PDO;
use PDOException;

class Carrito extends Modelo
{
   protected int $carrito_id;
   protected int $usuario_fk;

   /** @var array|Productos[] */
   protected array $productos = [];

   protected string $table = "carrito";
   protected string $primaryKey = "carrito_id";

   /** @var array|string[] La lista de propiedades que pueden cargarse dinámicamente desde un array generado desde la base de datos. */
   protected array $properties = ['carrito_id', 'usuario_fk'];

   protected Usuario $usuario;
   
   /**
    * Obtiene el carrito que corresponde al usuario indicado.
    * 
    * @param int $usuario_fk
    * @return Carrito|null
    */
   public function obtenerCarrito($usuario_fk): ?Carrito
   {
      $db = DBConexion::getConexion();
      $query = "SELECT * FROM carrito
                WHERE usuario_fk = ?";
      $stmt = $db->prepare($query);
      $stmt->execute([$usuario_fk]);
      $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);

      $carrito = $stmt->fetch();

      return $carrito ? $carrito : null;
   }

   /**
    * Crea un carrito en la base de datos.
    * Si falla el INSERT, se lanza una PDOException.
    *
    * @param array $dato
    * @return void
    * @throws PDOException
    */
   public function crear(array $dato)
   {
      $db = DBConexion::getConexion();
      DBConexion::transaction(function() use($db, $dato){
         $query = "INSERT INTO carrito (usuario_fk)
                  VALUES (:usuario_fk);";
         $stmt = $db->prepare($query);
         $stmt->execute([
            'usuario_fk' => $dato['usuario_fk'],
         ]);

         $carritoId = $db->lastInsertId();
         $this->crearItems($carritoId, $dato);
      });
   }

   /**
    * Inserta los items del carrito. 
    * Debe recibir el id del carrito, y un array con los datos del item.
    *
    * @param int $carritoId
    * @param array $dato
    * @return void
    */
   public function crearItems(int $carritoId, array $dato)
   {
      $insertValores = [
         'producto_fk' => $dato['producto_fk'],
         'carrito_fk'  => $carritoId,
         'cantidad'    => $dato['cantidad'],
         'subtotal'    => $dato['subtotal'],
      ];
      $query = "INSERT INTO detalle_item_carrito (producto_fk, carrito_fk, cantidad, subtotal)
               VALUES (:producto_fk, :carrito_fk, :cantidad, :subtotal);";
      DBConexion::executeQuery($query, $insertValores);
   }

   /**
    * Obtiene los items del carrito de la base de datos.
    * 
    * @return void
    */
   public function cargarItems($carrito_fk)
   {
      $db = DBConexion::getConexion();
      $query = "SELECT p.nombre, p.descripcion, p.imagen, p.imagen_descripcion, p.mostrar, dic.* FROM detalle_item_carrito dic
               INNER JOIN productos p ON dic.producto_fk = p.producto_id
               WHERE dic.carrito_fk = ?";
      $stmt = $db->prepare($query);
      $stmt->execute([$carrito_fk]);
      $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);

      return $stmt->fetchAll();
   }

   /**
    * Elimina el carrito.
    *
    * @return void
    * @throws PDOException
    */
   public function eliminar(): void
   {
      DBConexion::transaction(function(){
         $this->removerItems();
         $query = "DELETE FROM carrito
                  WHERE carrito_id = ?";
         DBConexion::executeQuery($query, [$this->getCarritoId()]);
      });
   }

   /**
    * Remueve las asociaciones de los items de este carrito.
    *
    * @return void
    */
   protected function removerItems()
   {
      $db = DBConexion::getConexion();
      $query = "DELETE FROM detalle_item_carrito
               WHERE carrito_fk = ?";
      $db->prepare($query)->execute([$this->getCarritoId()]);
   }

   /**
    * Setters y Getters
    */

   /**
    * @return string|null
    */
   public function getNombre(): ?string 
   {
      return $this->nombre;
   }

   /**
    * @return int|null
    */
   public function getSubtotal(): ?int 
   {
      return $this->subtotal;
   }

   /**
    * @return int|null
    */
   public function getCantidad(): ?int 
   {
      return $this->cantidad;
   }

   /**
    * @return string|null
    */
   public function getImagen(): ?string
   {
      return $this->imagen;
   }

   /**
    * @return string|null
    */     
   public function getImagenDescripcion(): ?string
   {
      return $this->imagen_descripcion;
   } 

   /**
    * @return int|null
    */
   public function getProductoFk(): ?int 
   {
      return $this->producto_fk;
   }

   /**
    * @return int|null
    */
   public function getCarritoFk(): ?int 
   {
      return $this->carrito_fk;
   }

   /**
    * @return int|null
    */
   public function getCarritoId(): ?int
   {
      return $this->carrito_id;
   }

   /**
    * @return int|null
    */
   public function getUsuarioFk(): ?int
   {
      return $this->usuario_fk;
   }

   /**
    * @param int $carrito_id
    */
   public function setCarritoId(int $carrito_id): void
   {
      $this->carrito_id = $carrito_id;
   }

   /**
    * @param int $carrito_id
    */
   public function setUsuarioFk(): ?int
   {
      $this->usuario_fk = $usuario_fk;
   }

   /** 
    * @param array $producto_fk
    */
   public function setProductosFk(array $producto_fk): void 
   {
      $this->producto_fk = $producto_fk;
   }

   /**
    * @return array|Productos[]
    */
   public function getProductos(): array 
   {
      return $this->productos;
   }
}