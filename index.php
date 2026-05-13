<?php
require_once 'db.php';

// Načítanie všetkých hier aj s názvom platformy (použijeme JOIN)
$sql = "SELECT hry.*, platformy.nazov AS platforma_nazov 
        FROM hry 
        JOIN platformy ON hry.platforma_id = platformy.id";
$vysledok = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Môj Katalóg Hier</title>
</head>
<body>
    <h1>Moja zbierka videohier</h1>
    <a href="pridaj.php" style="font-size: 24px; text-decoration: none;">➕ Pridať hru</a>

    <table border="1" style="margin-top: 20px; width: 100%;">
        <tr>
            <th>Názov</th>
            <th>Žáner</th>
            <th>Rok</th>
            <th>Platforma</th>
        </tr>
        <?php
        if ($vysledok->num_rows > 0) {
            while($row = $vysledok->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['nazov'] . "</td>
                        <td>" . $row['zaner'] . "</td>
                        <td>" . $row['rok_vydania'] . "</td>
                        <td>" . $row['platforma_nazov'] . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Zatiaľ nemáš žiadne hry.</td></tr>";
        }
        ?>
    </table>
</body>
</html>