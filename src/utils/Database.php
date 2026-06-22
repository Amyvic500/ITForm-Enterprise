&lt;?php
/**
 * Database Connection Class
 * Handles SQL Server and MySQL connections
 */

class Database {
    
    private $connection;
    private $driver;
    private $config;
    private $statement;
    private $error;
    
    public function __construct($config) {
        $this->config = $config;
        $this->driver = $config['driver'] ?? 'sqlsrv';
        $this->connect();
    }
    
    /**
     * Establish database connection
     */
    private function connect() {
        try {
            switch($this->driver) {
                case 'sqlsrv':
                    $this->connectSQLServer();
                    break;
                case 'mysql':
                    $this->connectMySQL();
                    break;
                default:
                    throw new Exception("Unsupported database driver: {$this->driver}");
            }
        } catch(Exception $e) {
            $this->error = $e->getMessage();
            log_error("Database Connection Error: " . $this->error);
            throw $e;
        }
    }
    
    /**
     * Connect to SQL Server
     */
    private function connectSQLServer() {
        $serverName = $this->config['host'] . ',' . $this->config['port'];
        $connectionOptions = [
            'Database' => $this->config['database'],
            'Uid' => $this->config['username'],
            'PWD' => $this->config['password'],
            'TrustServerCertificate' => true,
            'Encrypt' => true,
        ];
        
        $this->connection = sqlsrv_connect($serverName, $connectionOptions);
        
        if($this->connection === false) {
            throw new Exception("SQL Server Connection Failed: " . print_r(sqlsrv_errors(), true));
        }
    }
    
    /**
     * Connect to MySQL
     */
    private function connectMySQL() {
        try {
            $dsn = "mysql:host=" . $this->config['host'] . 
                   ";port=" . $this->config['port'] . 
                   ";dbname=" . $this->config['database'] .
                   ";charset=" . $this->config['charset'];
            
            $this->connection = new PDO(
                $dsn,
                $this->config['username'],
                $this->config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_PERSISTENT => false,
                ]
            );
        } catch(PDOException $e) {
            throw new Exception("MySQL Connection Failed: " . $e->getMessage());
        }
    }
    
    /**
     * Execute query
     */
    public function query($sql, $params = []) {
        try {
            if($this->driver === 'sqlsrv') {
                $this->statement = sqlsrv_query($this->connection, $sql, $params);
                if($this->statement === false) {
                    throw new Exception("Query Error: " . print_r(sqlsrv_errors(), true));
                }
            } else {
                $this->statement = $this->connection->prepare($sql);
                $this->statement->execute($params);
            }
            return $this;
        } catch(Exception $e) {
            $this->error = $e->getMessage();
            log_error("SQL Query Error: " . $this->error . " | SQL: " . $sql);
            throw $e;
        }
    }
    
    /**
     * Fetch single row
     */
    public function fetch() {
        try {
            if($this->driver === 'sqlsrv') {
                return sqlsrv_fetch_array($this->statement, SQLSRV_FETCH_ASSOC);
            } else {
                return $this->statement->fetch();
            }
        } catch(Exception $e) {
            log_error("Fetch Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Fetch all rows
     */
    public function fetchAll() {
        try {
            $results = [];
            if($this->driver === 'sqlsrv') {
                while($row = sqlsrv_fetch_array($this->statement, SQLSRV_FETCH_ASSOC)) {
                    $results[] = $row;
                }
            } else {
                $results = $this->statement->fetchAll();
            }
            return $results;
        } catch(Exception $e) {
            log_error("FetchAll Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Execute insert/update/delete
     */
    public function execute($sql, $params = []) {
        try {
            if($this->driver === 'sqlsrv') {
                $this->statement = sqlsrv_query($this->connection, $sql, $params);
                if($this->statement === false) {
                    throw new Exception("Execute Error: " . print_r(sqlsrv_errors(), true));
                }
                return true;
            } else {
                $stmt = $this->connection->prepare($sql);
                return $stmt->execute($params);
            }
        } catch(Exception $e) {
            log_error("Execute Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get last insert ID
     */
    public function lastInsertId() {
        try {
            if($this->driver === 'sqlsrv') {
                $result = sqlsrv_query($this->connection, "SELECT @@IDENTITY AS id");
                $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
                return $row['id'] ?? null;
            } else {
                return $this->connection->lastInsertId();
            }
        } catch(Exception $e) {
            log_error("LastInsertId Error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Begin transaction
     */
    public function beginTransaction() {
        try {
            if($this->driver === 'sqlsrv') {
                sqlsrv_begin_transaction($this->connection);
            } else {
                $this->connection->beginTransaction();
            }
        } catch(Exception $e) {
            log_error("BeginTransaction Error: " . $e->getMessage());
        }
    }
    
    /**
     * Commit transaction
     */
    public function commit() {
        try {
            if($this->driver === 'sqlsrv') {
                sqlsrv_commit($this->connection);
            } else {
                $this->connection->commit();
            }
        } catch(Exception $e) {
            log_error("Commit Error: " . $e->getMessage());
        }
    }
    
    /**
     * Rollback transaction
     */
    public function rollback() {
        try {
            if($this->driver === 'sqlsrv') {
                sqlsrv_rollback($this->connection);
            } else {
                $this->connection->rollBack();
            }
        } catch(Exception $e) {
            log_error("Rollback Error: " . $e->getMessage());
        }
    }
    
    /**
     * Get the connection
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Get row count
     */
    public function rowCount() {
        try {
            if($this->driver === 'sqlsrv') {
                return sqlsrv_num_rows($this->statement);
            } else {
                return $this->statement->rowCount();
            }
        } catch(Exception $e) {
            return 0;
        }
    }
    
    /**
     * Close connection
     */
    public function close() {
        try {
            if($this->driver === 'sqlsrv') {
                sqlsrv_close($this->connection);
            } else {
                $this->connection = null;
            }
        } catch(Exception $e) {
            log_error("Close Error: " . $e->getMessage());
        }
    }
    
    /**
     * Get error message
     */
    public function getError() {
        return $this->error;
    }
    
    /**
     * Destructor
     */
    public function __destruct() {
        $this->close();
    }
}

/**
 * Simple logging function
 */
function log_error($message) {
    $logFile = __DIR__ . '/../../storage/logs/error.log';
    $timestamp = date('Y-m-d H:i:s');
    error_log("[$timestamp] $message\n", 3, $logFile);
}

?&gt;
