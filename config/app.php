&lt;?php
/**
 * Application Configuration
 */

return [
    
    // Application Meta
    'name'       => 'ITForm Enterprise',
    'version'    => '1.0.0',
    'environment'=> env('APP_ENV', 'development'),
    'debug'      => env('APP_DEBUG', false),
    'url'        => env('APP_URL', 'http://localhost/itform'),
    
    // Security
    'app_key'    => env('APP_KEY', 'SomeRandomString32CharactersLong'),
    'cipher'     => 'AES-256-CBC',
    'csrf_protection' => true,
    'session_timeout' => env('SESSION_TIMEOUT', 30), // minutes
    
    // Session Configuration
    'session' => [
        'driver'   => 'file', // file, database, redis
        'lifetime' => env('SESSION_TIMEOUT', 30) * 60, // Convert to seconds
        'path'     => storage_path('sessions'),
        'name'     => 'ITFORM_SESSION',
        'cookie'   => [
            'http_only' => true,
            'secure'    => env('APP_ENV') === 'production',
            'same_site' => 'Lax',
        ]
    ],
    
    // Logging
    'logging' => [
        'enabled'  => env('ENABLE_LOGGING', true),
        'level'    => env('LOG_LEVEL', 'info'), // debug, info, warning, error, critical
        'path'     => storage_path('logs'),
        'max_files'=> 14, // Keep logs for 14 days
    ],
    
    // Email Configuration
    'mail' => [
        'driver'       => env('MAIL_DRIVER', 'smtp'),
        'host'         => env('MAIL_HOST', 'smtp.mailtrap.io'),
        'port'         => env('MAIL_PORT', 465),
        'username'     => env('MAIL_USERNAME', ''),
        'password'     => env('MAIL_PASSWORD', ''),
        'encryption'   => env('MAIL_ENCRYPTION', 'ssl'), // null, tls, ssl
        'from_address' => env('MAIL_FROM_ADDRESS', 'noreply@itform.local'),
        'from_name'    => env('MAIL_FROM_NAME', 'ITForm System'),
        'enabled'      => env('ENABLE_EMAIL_NOTIFICATIONS', true),
    ],
    
    // File Upload
    'upload' => [
        'max_file_size'     => env('MAX_FILE_SIZE_MB', 10) * 1024 * 1024, // bytes
        'allowed_extensions'=> ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'gif'],
        'upload_directory'  => storage_path('uploads'),
        'temp_directory'    => storage_path('temp'),
    ],
    
    // Approval Settings
    'approval' => [
        'timeout_days'        => env('APPROVAL_TIMEOUT_DAYS', 7),
        'reminder_days'       => env('APPROVAL_REMINDER_DAYS', 3),
        'enable_escalation'   => true,
        'escalation_days'     => 5,
        'auto_escalate_to_md' => true,
    ],
    
    // Pagination
    'pagination' => [
        'per_page' => 15,
        'paginate_options' => [10, 15, 25, 50],
    ],
    
    // Audit Trail
    'audit' => [
        'enabled'        => true,
        'log_all_actions'=> true,
        'retention_days' => 365, // Keep audit logs for 1 year
        'excluded_tables'=> ['tbl_sessions', 'tbl_logs'],
    ],
    
    // Performance
    'cache' => [
        'driver'   => 'file', // file, database, redis
        'ttl'      => 3600, // Cache time-to-live in seconds
        'path'     => storage_path('cache'),
    ],
    
    // API Configuration (if API routes are added in future)
    'api' => [
        'prefix'            => '/api',
        'version'           => 'v1',
        'rate_limit'        => 100, // requests per minute
        'token_expiry'      => 86400 * 30, // 30 days
    ],
    
    // Default Pagination
    'items_per_page' => 15,
    
    // Timezone
    'timezone' => 'Africa/Lagos',
    
    // Date Format
    'date_format' => 'Y-m-d',
    'time_format' => 'H:i:s',
    'datetime_format' => 'Y-m-d H:i:s',
    
    // Feature Flags
    'features' => [
        'two_factor_auth'      => false, // Future enhancement
        'api_integration'      => false, // Future enhancement
        'sms_notifications'    => false,
        'push_notifications'   => false,
        'advanced_reporting'   => true,
        'analytics'           => true,
    ],
];
