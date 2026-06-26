<?php
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REQUEST_URI'] = '/itform/auth/login';
$_POST['email'] = 'victoria@itform.test';
$_POST['password'] = 'Victoria@2026';
require 'index.php';
?>
