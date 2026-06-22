<?php
namespace App\Utils;

class Database
{
    protected static $instance = null;
    protected $connection;

    private function __construct()
    {
        try {
            $this->connection = new \PDO(
                'sqlsrv:Server=IT-VICTORIA\SQLEXPRESS;Database=ITForm_DB',
                'itform_user',
                'ITFORMdb2026@'
            );
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->connection;
    }
}
