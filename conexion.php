<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "base_datos";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>