<?php

namespace App\DB;

use PDO;

/**
 * Wrapper de PDO en modo "Singleton".
 */
class DBConexion
{
    public const DB_HOST = "127.0.0.1";
    public const DB_USER = 'root';
    public const DB_PASS = '';
    public const DB_NAME = 'dw3_soler_julieta';

    /** @var PDO */
    private static ?PDO $db = null;

    private static function conectar()
    {   
        $dsn = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME . ';charset=utf8mb4';
        self::$db = new PDO($dsn, self::DB_USER, self::DB_PASS);
        self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Obtiene la conexión a la base de datos.
     * @return PDO
     */
    public static function getConexion(): PDO
    {
        if(!self::$db){
            self::conectar();
        }
        return self::$db;
    }

    /**
     * Prepara un statement a partir del $query
     * 
     * @param string $query
     * @return \PDOStatement
     */
    public static function getStatement(string $query): \PDOStatement
    {
        return self::getConexion()->prepare($query);
    }

    /**
     * Prepara y ejecuta la consulta $query con opcionalmente los parámetros $params y retorna el PDOStatement.
     * 
     * @param string $query
     * @param array|null $params
     * @return \PDOStatement
     */
    public static function executeQuery(string $query, ?array $params = null): \PDOStatement
    {
        $stmt = self::getStatement($query);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Ejecuta la función provista como argumento englobada en una transacción de SQL.
     * Si la función falla con un Throwable, la transacción se cancela y se lanza un Throwable.
     * 
     * @param \Closure $function
     * @return void
     * @throws \Exception
     */
    public static function transaction(\Closure $function)
    {
        $db = self::getConexion();
        $db->beginTransaction();
        try {
            $function();
            $db->commit();
        } catch (\Throwable $th) {
            $db->rollBack();
            throw $th;
        }
    }
}
