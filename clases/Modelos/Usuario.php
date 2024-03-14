<?php

namespace App\Modelos;

use App\DB\DBConexion;
use PDO;

class Usuario extends Modelo
{
    protected int $usuario_id;
    protected int $rol_id;
    protected string $email;
    protected string $contrasena;
    protected ?string $nombre;
    protected ?string $apellido;

    protected UsuarioRol $usuario_rol;

    protected string $table = "usuarios";
    protected string $primaryKey = "usuario_id";

    /** @var array|string[] La lista de propiedades que pueden cargarse dinámicamente desde un array generado desde la base de datos. */
    protected array $properties = ['usuario_id', 'rol_id', 'email', 'contrasena', 'nombre', 'apellido'];

    /**
     * Retorna todos los usuarios
     * 
     * @return array|self[] La lista de usuarios.
     */
    public function todoContenido(): array
    {
        $db = DBConexion::getConexion();
        $query = "SELECT * FROM usuarios u
            INNER JOIN roles r ON u.rol_id = r.rol_id";
        $stmt = $db->prepare($query);
        $stmt->execute();

        $salida = [];

        while($fila = $stmt->fetch(PDO::FETCH_ASSOC)){
            $obj = new Usuario();
            $obj->loadProperties($fila);

            $usuario_rol = new UsuarioRol();
            $usuario_rol->loadProperties($fila);
            $obj->setRolUsuario($usuario_rol);

            $salida[] = $obj;
        }
        return $salida;
    }

    /**
     * Obtiene un usuario por su email. 
     * 
     * @param string $email
     * @return Usuario|null
     */
    public function traerPorEmail($email): ?Usuario
    {
        $db = DBConexion::getConexion();
        $query = "SELECT * FROM usuarios
                WHERE email = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$email]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
            
        $usuario = $stmt->fetch();

        return $usuario ? $usuario : null;
    }

    /**
     * Crea un nuevo usuario.
     * 
     * @param array $dato
     * @return void
     */
    public function crear(array $dato)
    {
        $db = DBConexion::getConexion();
        DBConexion::transaction(function() use($db, $dato){
            $query = "INSERT INTO usuarios (email, contrasena, rol_id, nombre, apellido)
                VALUES (:email, :contrasena, :rol_id, :nombre, :apellido)";
                
            $db->prepare($query)->execute([
                'email'      => $dato['email'],
                'contrasena' => $dato['contrasena'],
                'rol_id'     => $dato['rol_id'],
                'nombre'     => $dato['nombre'],
                'apellido'   => $dato['apellido']
            ]);
        });
    }

    /**
     * Actualiza la contraseña del usuario
     * @param string $contrasena
     * @return void
     * @throws \PDOException
     */
    public function editarContrasena(string $contrasena)
    {
        $db = DBConexion::getConexion();
        DBConexionDB::transaction(function() use($db, $contrasena){
            $query = "UPDATE usuarios
                SET contrasena = :contrasena
                WHERE usuario_id = :usuario_id";
            $db->prepare($query)->execute([
            'contrasena' => $contrasena,
            'usuario_id' => $this->getUsuarioId(),
            ]);
        });
    }

   /**
   * Getters y Setters
   */
   public function getNombreCompleto(): ?string 
   {
        return $this->getApellido() . ", " . $this->getNombre();
   }

   /**
    * @return int
    */
   public function getUsuarioId(): int
   {
       return $this->usuario_id;
   }

   /**
    * @param int $usuario_id
    */
   public function setUsuarioId(int $usuario_id): void 
   {
       $this->usuario_id = $usuario_id;
   }

   /**
    * @return int
    */
   public function getRolId(): int 
   {
    return $this->rol_id;
   }

   /**
    * @param int $rol_id
    */
    public function setRolId(int $rol_id): void 
    {
        $this->rol_id = $rol_id;
    }

   /**
    * @return string
    */
   public function getEmail(): string 
   {
       return $this->email;
   }

   /**
    * @param string $email
    */
   public function setEmail(string $email): void 
   {
       $this->email = $email;
   }

   /**
    * @return string
    */
   public function getContrasena(): string 
   {
       return $this->contrasena;
   }

   /**
    * @param string $contrasena
    */
   public function setContrasena(string $contrasena): void 
   {
       $this->contrasena = $contrasena;
   }

    /**
     * @return string|null
     */
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    /**
     * @param string|null $nombre
     */
    public function setNombre(?string $nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return string|null
     */
    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    /**
     * @param string|null $apellido
     */
    public function setApellido(?string $apellido): void
    {
        $this->apellido = $apellido;
    }

    /**
     * @return UsuarioRol
     */
    public function getRolUsuario(): UsuarioRol 
    {
        return $this->usuario_rol;
    }

    /**
     * @return UsuarioRol $usuario_rol
     */
    public function setRolUsuario(UsuarioRol $usuario_rol): void 
    {
        $this->usuario_rol = $usuario_rol;
    }
}