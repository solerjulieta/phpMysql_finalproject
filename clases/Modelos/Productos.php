<?php

namespace App\Modelos;

use App\DB\DBConexion;
use App\Paginacion\Paginador;
use PDO;
use PDOException;

class Productos extends Modelo
{
    protected int $producto_id;
    protected int $categoria_fk;
    protected int $usuario_id;
    protected string $nombre;
    protected string $descripcion;
    protected int $precio;
    protected string $imagen;
    protected string $imagen_descripcion;
    protected bool $recomendado;
    protected bool $mostrar;
    protected array $caracteristica_id = [];

    protected ProductoCategoria $prod_categoria;
    /** @var array|Caracterististica[] */
    protected array $caracteristicas = [];

    protected Paginador $paginador;

    protected string $table = "productos";
    protected string $primaryKey = "producto_id";

    /**
     * @var array|string[] Lista de propiedades que pueden cargarse dinámicamente desde un array
     * generado desde la base de datos.
     */
    protected array $properties = ['producto_id', 'categoria_fk', 'usuario_id', 'nombre', 'descripcion', 'precio', 'imagen', 'imagen_descripcion', 'recomendado', 'mostrar'];

    /**
    * Retorna todos los productos de la tienda.
    * @param int $registrosPorPagina
    * @return array|Productos[] Lista de productos
    */
    public function todoContenido(?array $where = null, int $registrosPorPagina = 20): array
    {
        $db = DBConexion::getConexion();

        $this->paginador = new Paginador($registrosPorPagina);

        $queryWhere = "";
        $whereParameters = [];

        if($where !== null) {
            $whereConditions = [];
            foreach($where as [$column, $operador, $value]) {
                $whereParameters[$column] = $value;
                $whereConditions[] = "{$column} {$operador} :{$column}";
            }
            $queryWhere .= ' WHERE ' . implode(' AND ', $whereConditions);
        }

        $query = "SELECT p.*, cat.* FROM productos p
                INNER JOIN categoria cat ON p.categoria_fk = cat.categoria_id
                {$queryWhere}
                GROUP BY p.producto_id
                LIMIT {$this->paginador->getRegistrosPorPagina()} OFFSET {$this->paginador->getRegistroInicial()}";

        $stmt = $db->prepare($query);
        $stmt->execute($whereParameters);

        $salida = [];

        while($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $salida[] = $this->crearProductoDeRegistro($fila);
        }

        $queryCount = "SELECT COUNT(*) AS 'total' FROM productos
                     {$queryWhere}";
        $stmtCount = $db->prepare($queryCount);
        $stmtCount->execute($whereParameters);
        $fila = $stmtCount->fetch();
        $this->paginador->setRegistroTotal($fila['total']);

        return $salida;
    }

    /**
     * Genera una instancia de Producto a partir de los datos de una fila de la base de datos.
     * 
     * @param array $fila
     * @return self
     */
    public function crearProductoDeRegistro(array $fila): self
    {
        $obj = new Productos();
        $obj->loadProperties($fila);

        $prod_categoria = new ProductoCategoria();
        $prod_categoria->loadProperties($fila);
        $obj->setProdCategoria($prod_categoria);

        return $obj;  
    }

    /**
     * Retorna los productos que no están ocultos.
     * 
     * @param array|null $where
     * @param int $registroPorPagina
     * @return self[]|array
     */
    public function mostrar(?array $where = null, int $registroPorPagina = 20): array
    {
        $whereSearch = [
            ['mostrar', '=', 1], 
        ];
        //Fusiona, si hace falta, las condiciones de búsqueda. 
        if(is_array($where) && count($where) > 0){
            $whereSearch = array_merge($whereSearch, $where);
        }
        return $this->todoContenido($whereSearch, $registroPorPagina);
    }

    /**
     * Retorna los productos recomendados.
     * 
     * @param array|null $where
     * @param int $registroPorPagina
     * @return self[]|array
     */
    public function recomendado(?array $where = null, int $registroPorPagina = 20): array
    {
        $whereSearch = [
            ['recomendado', '=', 1], 
        ];
        //Fusiona, si hace falta, las condiciones de búsqueda. 
        if(is_array($where) && count($where) > 0){
            $whereSearch = array_merge($whereSearch, $where);
        }
        return $this->todoContenido($whereSearch, $registroPorPagina);
    }

    /**
     * Retorna los productos recomendados y que no están ocultos.
     * 
     * @param array|null $where
     * @param int $registroPorPagina
     * @return self[]|array
     */
    public function mostrarYrecomendados(?array $where = null, int $registroPorPagina = 20): array
    {
        $whereSearch = [
            ['mostrar', '=', 1], 
            ['recomendado', '=', 1], 
        ];
        //Fusiona, si hace falta, las condiciones de búsqueda. 
        if(is_array($where) && count($where) > 0){
            $whereSearch = array_merge($whereSearch, $where);
        }
        return $this->todoContenido($whereSearch, $registroPorPagina);
    }

    /**
     * Crea un nuevo producto en la base de datos.
     * Si falla el insert, se lanza una PDOException.
     * 
     * @param array $dato
     * @return void
     * @throws PDOException
     */
    public function crear(array $dato)
    {
        $db = DBConexion::getConexion();
        DBConexion::transaction(function() use ($db, $dato){
            $query = "INSERT INTO productos (categoria_fk, usuario_id, nombre, descripcion, precio, imagen, imagen_descripcion, recomendado, mostrar)
                    VALUES (:categoria_fk, :usuario_id, :nombre, :descripcion, :precio, :imagen, :imagen_descripcion, :recomendado, :mostrar);";
            $stmt = $db->prepare($query);
            $stmt->execute([
                'categoria_fk'       => $dato['categoria_fk'],
                'usuario_id'         => $dato['usuario_id'],
                'nombre'             => $dato['nombre'],
                'descripcion'        => $dato['descripcion'],
                'precio'             => $dato['precio'],
                'imagen'             => $dato['imagen'],
                'imagen_descripcion' => $dato['imagen_descripcion'],
                'recomendado'        => $dato['recomendado'],
                'mostrar'            => $dato['mostrar'],
            ]);

            if(!empty($dato['caracteristicas'])){
                $productoId = $db->lastInsertId();
                $this->grabarCaracteristicas($productoId, $dato['caracteristicas']);            
            }
        });
    }

    /**
     * Inserta las características para el producto.
     * Debe recibir el id del producto, y un array de ids de las características.
     * 
     * @param int $productoId
     * @param array $caracteristicas
     * @return void
     */
    protected function grabarCaracteristicas(int $productoId, array $caracteristicas)
    {
        $insertPares = [];
        $insertValores = [];
        foreach($caracteristicas as $caracteristicasId){
            $insertPares[] = "(?, ?)";
            $insertValores[] = $productoId;
            $insertValores[] = $caracteristicasId;
        }
        $query = "INSERT INTO productos_tienen_caracteristicas (producto_id, caracteristicas_id)
                 VALUES " . implode(', ', $insertPares);
        DBConexion::executeQuery($query, $insertValores);
    }

    /**
     * Obtiene las características de los productos de la base de datos.
     * 
     * @return void
     */
    public function cargarCaracteristicas()
    {
        $query = 'SELECT c.* FROM productos_tienen_caracteristicas ptc
                  INNER JOIN caracteristicas c ON ptc.caracteristicas_id = c.caracteristicas_id
                  WHERE ptc.producto_id = ?';
        $stmt = DBConexion::executeQuery($query, [$this->getProductoId()]);

        $caractId = [];
        $caracteristicas = [];
        while($fila = $stmt->fetch(PDO::FETCH_ASSOC)){
            $caractId[] = $fila['caracteristicas_id'];
            $caracteristica = new Caracteristica();
            $caracteristica->loadProperties($fila);
            $caracteristicas[]= $caracteristica;
        }

        $this->setCaractId($caractId);
        $this->setCaracteristicas($caracteristicas);
    }

    /**
     * Actualiza los datos de producto. 
     *
     * @param array $dato
     * @return void
     * @throws PDOException
     */
    public function editar(array $dato): void
    {
       $db = DBConexion::getConexion();
       DBConexion::transaction(function() use($db, $dato){
            $query = "UPDATE productos
            SET categoria_fk        = :categoria_fk,
                usuario_id          = :usuario_id,
                nombre              = :nombre,
                descripcion         = :descripcion,
                precio              = :precio,
                imagen              = :imagen,
                imagen_descripcion  = :imagen_descripcion,
                recomendado         = :recomendado
            WHERE producto_id = :producto_id";
            
            $db->prepare($query)->execute([
            'producto_id'        => $this->getProductoId(),
            'categoria_fk'       => $dato['categoria_fk'],
            'usuario_id'         => $dato['usuario_id'],
            'nombre'             => $dato['nombre'],
            'descripcion'        => $dato['descripcion'],
            'precio'             => $dato['precio'],
            'imagen'             => $dato['imagen'], 
            'imagen_descripcion' => $dato['imagen_descripcion'],
            'recomendado'        => $dato['recomendado'],           
            ]);

            //Actualizamos, si hay, características.
            if(isset($dato['caracteristicas'])){
            $this->actualizarCaracteristicas($dato['caracteristicas'] ?? []);
            }
       });
    }

    /**
     * Actualiza las características del producto.
     * 
     * @param array $caracteristicas
     * @return void
     */
    protected function actualizarCaracteristicas(array $caracteristicas)
    {
        $this->removerCaracteristicas();
        if(!empty($caracteristicas)){
            $this->grabarCaracteristicas($this->getProductoId(), $caracteristicas);
        }
    }

    /**
     * Oculta un producto.
     * 
     * @param array $dato
     * @return void
     * @throws PDOException
     */
    public function ocultar(array $dato): void
    {
        $db = DBConexion::getConexion();
        DBConexion::transaction(function() use($db, $dato){
            $query = "UPDATE productos
                SET mostrar = :mostrar
                WHERE producto_id = :producto_id";

            $db->prepare($query)->execute([
                'producto_id' => $this->getProductoId(),
                'mostrar' => $dato['mostrar'],
            ]);
        });
    }

    /**
     * Elimina el producto
     * @return void
     * @throws PDOException
     */
    public function eliminar(): void
    {
        DBConexion::transaction(function(){
            $this->removerCaracteristicas();
            $this->removerProductoDelCarrito();
            $query = "DELETE FROM productos
                    WHERE producto_id = ?";
            DBConexion::executeQuery($query, [$this->getProductoId()]);
        });
    }

    /**
     * Remueve las asociaciones de las características del producto.
     * 
     * @return void
     */
    protected function removerCaracteristicas()
    {
        $db = DBConexion::getConexion();
        $query = 'DELETE FROM productos_tienen_caracteristicas
                  WHERE producto_id = ?';
        $db->prepare($query)->execute([$this->getProductoId()]);
    }

    /**
     * Remueve, si hay, las asociaciones de los productos del carrito.
     * 
     * @return void 
     */
    protected function removerProductoDelCarrito()
    {
        $db = DBConexion::getConexion();
        $query = 'DELETE FROM detalle_item_carrito
                  WHERE producto_fk = ?';
        $db->prepare($query)->execute([$this->getProductoId()]);
    }

    /**
     * Setters y Getters
     */

    /**
     * @return int|null
     */
    public function getProductoId(): ?int
    {
        return $this->producto_id;
    }

    /**
     * @return int|null
     */
    public function getCategoriaId(): ?int
    {
        return $this->categoria_fk;
    }    

    /**
     * @return int|null
     */
    public function getUsuarioId(): ?int
    {
        return $this->usuario_id;
    } 

    /**
     * @return string|null
     */
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    /**
     * @return string|null
     */    
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    /**
     * @return array
     */
    public function getCaracteristicas(): ?array
    {
        return $this->caracteristicas;
    }

    /**
     * @return int|null
     */
    public function getPrecio(): ?int 
    {
        return $this->precio;
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
     * @return bool|null
     */
    public function getRecomendado(): ?bool 
    {
        return $this->recomendado;
    }

    /**
     * @return bool|null
     */
    public function getMostrar(): ?bool
    {
        return $this->mostrar;
    }

    /**
     * @return ProductoCategoria
     */
    public function getProdCategoria(): ProductoCategoria
    {
        return $this->prod_categoria;
    }

    /**
     * @return int|null
     */    
    public function getCaractId(): array 
    {
        return $this->caracteristica_id;
    }

    /**
     * @return Paginador
     */
    public function getPaginador(): Paginador 
    {
        return $this->paginador;
    }

    /**
     * @param int $producto_id
     */
    public function setProductoId(): void 
    {
        $this->producto_id = $producto_id;
    }

    /**
     * @param int $categoria_fk
     */
    public function setCategoriaId(): void 
    {
        $this->categoria_fk = $categoria_fk;
    }

    /**
     * @param int $usuario_id
     */
    public function setUsuarioId(): void 
    {
        $this->usuario_id = $usuario_id;
    }

    /**
     * @param string $nombre
     */
    public function setNombre(): void 
    {
        $this->nombre = $nombre;
    }

    /**
     * @param string $descripcion
     */    
    public function setDescripcion(): void 
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @param int $precio
     */
    public function setPrecio(): void 
    {
        $this->precio = $precio;
    }

    /**
     * @param string $descripcion
     */    
    public function setImagen(): void 
    {
        $this->imagen = $imagen;
    }

    /**
     * @param string $imagen_descripcion
     */
    public function setImagenDescripcion(string $imagen_descripcion): void
    {
        $this->imagen_descripcion = $imagen_descripcion;
    }

    /**
     * @param bool $descripcion
     */    
    public function setRecomendado(): void 
    {
        $this->recomendado = $recomendado;
    }

    /**
     * @param bool $mostrar
     */
    public function setMostrar(): void
    {
        $this->mostrar = $mostrar;
    }

    /**
     * @param ProductoCategoria $prod_categoria
     */
    public function setProdCategoria(ProductoCategoria $prod_categoria): void 
    {
        $this->prod_categoria = $prod_categoria;
    }

    /**
     * @param int $precio
     */    
    public function setCaractId(array $caracteristica_id): void 
    {
        $this->caracteristica_id = $caracteristica_id;
    }

    /**
     * @param array $etiquetas_fk
     */    
    public function setCaracteristicas(array $caracteristicas): void 
    {
        $this->caracteristicas = $caracteristicas;
    }
}