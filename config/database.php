&lt;?php
/**
 * Database Configuration
 * 
 * Configure your database connection details here
 * Supports SQL Server (Primary), MySQL (Alternative)
 */

return [
    'default' => env('DB_CONNECTION', 'sqlserver'),
    
    'connections' => [
        
        // SQL Server Configuration (Primary)
        'sqlserver' => [
            'driver'   => 'sqlsrv',
            'host'     => env('DB_HOST', 'localhost'),
            'port'     => env('DB_PORT', 1433),
            'database' => env('DB_NAME', 'ITForm_DB'),
            'username' => env('DB_USER', 'sa'),
            'password' => env('DB_PASS', 'YourPasswordHere'),
            'charset'  => 'UTF8',
            'prefix'   => '',
            'options'  => [
                'TrustServerCertificate' => true,
            ]
        ],
        
        // MySQL Alternative Configuration
        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', 'localhost'),
            'port'      => env('DB_PORT', 3306),
            'database'  => env('DB_NAME', 'itform'),
            'username'  => env('DB_USER', 'root'),
            'password'  => env('DB_PASS', ''),
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
        ],
    ],
];

/**
 * Environment Configuration
 * 
 * Place a .env file in root directory with these variables:
 * 
 * APP_ENV=development (development|production)
 * APP_DEBUG=true (true|false)
 * DB_CONNECTION=sqlserver (sqlserver|mysql)
 * DB_HOST=localhost
 * DB_PORT=1433
 * DB_NAME=ITForm_DB
 * DB_USER=sa
 * DB_PASS=your_password
 * SESSION_TIMEOUT=30 (minutes)
 * ENABLE_LOGGING=true
 * LOG_LEVEL=info (debug|info|warning|error)
 */
