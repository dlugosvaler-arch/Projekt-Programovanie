<?php
session_start();
require_once 'db.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $hra_id = intval($_GET['id']); 
    
    $sql = "DELETE FROM hry WHERE id = $hra_id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Chyba pri mazaní hry: " . $conn->error;
    }
} else {
    header("Location: index.php");
    exit();
}
?>