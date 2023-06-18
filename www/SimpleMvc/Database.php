<?php

namespace SimpleMvc;

use PDO;
use PDOException;

/**
 * Class Database
 *
 * This class manages database connections
 *
 * @example
 * $db = Database::getInstance(DB_HOST, DB_NAME, DB_USER, DB_PASS);
 */
class Database
{
    private PDO $db;

    private static array $instances = [];

    public function __construct(string $host, string $database, string $user, string $password)
    {
        $dsn = 'mysql:host=' . $host . ';dbname=' . $database;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        try {
            $this->db = new PDO($dsn, $user, $password, $options);
        } catch (PDOException $e) {
            die('Could not establish database connection: ' . $e->getMessage());
        }
    }

    public static function getInstance(string $name = '', string $host = '', string $dbname = '', string $user = '', string $pass = ''): Database
    {
        if ($name === '') {
            $name = 'default';
        }
        if (!isset(self::$instances[$name])) {
            self::$instances[$name] = new self($host, $dbname, $user, $pass);
        }

        return self::$instances[$name];
    }

    public function getConnection(): PDO
    {
        return $this->db;
    }
}