<?php
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/itform/auth/login';
$_GET['url'] = '/itform/auth/login';

echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "URL param: " . ($_GET['url'] ?? 'EMPTY') . "\n";

require 'index.php';
?>
