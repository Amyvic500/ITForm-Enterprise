<?php
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/itform/auth/login';
$_GET['url'] = '/itform/auth/login';

// Load without dispatching
require 'config/app.php';
require 'index.php';  // This loads routes

// Debug output
echo "=== ROUTE DEBUG ===\n";
echo "REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "URL param: " . ($_GET['url'] ?? 'NOT SET') . "\n";
echo "Parsed path: " . (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) . "\n";
?>
