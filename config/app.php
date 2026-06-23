<?php
return [
    'name'       => 'ITForm Enterprise',
    'version'    => '1.0.0',
    'environment'=> env('APP_ENV', 'development'),
    'debug'      => env('APP_DEBUG', false),
    'url'        => env('APP_URL', 'http://localhost/itform'),
    
    'app_key'    => env('APP_KEY', 'SomeRandomString32CharactersLong'),
    'cipher'     => 'AES-256-CBC',
    'csrf_protection' => true,
    'session_timeout' => env('SESSION_TIMEOUT', 30),
    
    'session' => [
        'driver'   => 'file',
        'lifetime' => env('SESSION_TIMEOUT', 30) * 60,
        'path'     => storage_path('sessions'),
        'name'     => 'ITFORM_SESSION',
        'cookie'   => [
            'http_only' => true,
            'secure'    => env('APP_ENV') === 'production',
            'same_site' => 'Lax',
        ]
    ],
    
    'logging' => [
        'enabled'  => env('ENABLE_LOGGING', true),
        'level'    => env('LOG_LEVEL', 'info'),
        'path'     => storage_path('logs'),
        'max_files'=> 14,
    ],
    
    'mail' => [
        'driver'       => env('MAIL_DRIVER', 'smtp'),
        'host'         => env('MAIL_HOST', 'smtp.mailtrap.io'),
        'port'         => env('MAIL_PORT', 465),
        'username'     => env('MAIL_USERNAME', ''),
        'password'     => env('MAIL_PASSWORD', ''),
        'encryption'   => env('MAIL_ENCRYPTION', 'ssl'),
        'from_address' => env('MAIL_FROM_ADDRESS', 'noreply@itform.local'),
        'from_name'    => env('MAIL_FROM_NAME', 'ITForm System'),
        'enabled'      => env('ENABLE_EMAIL_NOTIFICATIONS', true),
    ],
    
    'upload' => [
        'max_file_size'     => env('MAX_FILE_SIZE_MB', 10) * 1024 * 1024,
        'allowed_extensions'=> ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'],
        'upload_directory'  => storage_path('uploads'),
        'temp_directory'    => storage_path('temp'),
    ],
    
    'approval' => [
        'timeout_days'        => env('APPROVAL_TIMEOUT_DAYS', 7),
        'reminder_days'       => env('APPROVAL_REMINDER_DAYS', 3),
        'enable_escalation'   => true,
        'escalation_days'     => 5,
    ],
    
    'pagination' => [
        'per_page' => 15,
    ],
    
    'audit' => [
        'enabled'        => true,
        'log_all_actions'=> true,
        'retention_days' => 365,
    ],
    
    'timezone' => 'Africa/Lagos',
    'date_format' => 'Y-m-d',
    'datetime_format' => 'Y-m-d H:i:s',
    
    'features' => [
        'two_factor_auth'      => false,
        'advanced_reporting'   => true,
        'analytics'           => true,
    ],
];
