<?php
$host = "localhost";
$user = "root"; // Predvolené v XAMPP
$pass = "";     // Predvolené v XAMPP
$db   = "katalog_hier";

// Vytvorenie pripojenia
$conn = new mysqli($host, $user, $pass, $db);

// Kontrola pripojenia
if ($conn->connect_error) {
    die("Pripojenie zlyhalo: " . $conn->connect_error);
}

// Nastavenie kódovania na UTF-8 (aby fungovala diakritika)
$conn->set_charset("utf8");
?>