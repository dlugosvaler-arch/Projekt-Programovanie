<?php
$host = "localhost";
$user = "root"; 
$pass = "root";     
$db   = "katalog_hier";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Pripojenie zlyhalo: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>