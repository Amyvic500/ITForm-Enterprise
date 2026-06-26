<?php
$mysqli = new mysqli('localhost', 'root', '', '');
if ($mysqli->connect_error) {
    die('❌ Connection failed: ' . $mysqli->connect_error);
}
echo "✅ Connected to MySQL\n";

$mysqli->query('CREATE DATABASE IF NOT EXISTS itform');
$mysqli->select_db('itform');

$schema = file_get_contents('database/schema.sql');
$queries = explode(';', $schema);
$count = 0;

foreach ($queries as $query) {
    if (trim($query)) {
        if ($mysqli->query($query)) {
            $count++;
        }
    }
}

echo "✅ Database created\n";
echo "✅ Schema imported (" . $count . " statements)\n";
?>
