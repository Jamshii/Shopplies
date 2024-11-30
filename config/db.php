<?php
$host = 'localhost';
$user = 'root';
$password = 'Jef071803';
$dbname = 'shopplies_sample';
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
