<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'shopplies_new';
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
