<?php
namespace App\utils;

class Database
{
    private $connection;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        try {
            $host = 'IT-VICTORIA\SQLEXPRESS';
            $database = 'ITForm_DB';
            $username = 'itform_user';
            $password = 'ITForm@2026';

            $dsn = "sqlsrv:Server=" . $host . ";Database=" . $database;
            
            $this->connection = new \PDO(
                $dsn,
                $username,
                $password,
                array(
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                )
            );
        } catch (\PDOException $e) {
            die("Database Connection Failed: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (\PDOException $e) {
            die("Query Error: " . $e->getMessage());
        }
    }

    public function select($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    public function selectOne($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
}
?>