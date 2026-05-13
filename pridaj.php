<?php
require_once 'db.php'; // Pripojíme databázu

// Ak bol formulár odoslaný (metóda POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nazov = $_POST['nazov'];
    $zaner = $_POST['zaner'];
    $rok = $_POST['rok_vydania'];
    $platforma_id = $_POST['platforma_id'];
    $pouzivatel_id = 1; // Zatiaľ natvrdo nastavíme používateľa 1 (Jozef), neskôr urobíme prepínanie

    // Základná validácia
    if (!empty($nazov) && !empty($platforma_id)) {
        // SQL dotaz na vloženie dát (CREATE)
        $sql = "INSERT INTO hry (nazov, zaner, rok_vydania, platforma_id, pouzivatel_id) 
                VALUES ('$nazov', '$zaner', '$rok', '$platforma_id', '$pouzivatel_id')";
        
        if ($conn->query($sql) === TRUE) {
            echo "<p style='color:green;'>Hra bola úspešne pridaná!</p>";
        } else {
            echo "Chyba: " . $conn->error;
        }
    } else {
        echo "<p style='color:red;'>Vyplňte povinné polia (Názov a Platformu).</p>";
    }
}

// Načítanie platforiem pre výberové menu (Select)
$platformy_vysledok = $conn->query("SELECT * FROM platformy ORDER BY kategoria, nazov");
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Pridať hru</title>
</head>
<body>
    <h2>Pridať novú hru do knižnice</h2>
    
    <form method="POST" action="pridaj.php">
        <label>Názov hry:</label><br>
        <input type="text" name="nazov" required><br><br>

        <label>Žáner:</label><br>
<select name="zaner">
    <option value="">-- Vyberte žáner --</option>
    <option value="Akčná">Akčná</option>
    <option value="Adventúra">Adventúra</option>
    <option value="RPG">RPG</option>
    <option value="Športová">Športová</option>
    <option value="Simulátor">Simulátor</option>
    <option value="Stratégia">Stratégia</option>
    <option value="Horor">Horor</option>
</select><br><br>

        <label>Rok vydania:</label><br>
        <input type="number" name="rok_vydania"><br><br>

        <label>Platforma / PC Klient:</label><br>
        <select name="platforma_id" required>
            <option value="">-- Vyberte platformu --</option>
            <?php
            // Vypísanie možností z databázy
            if ($platformy_vysledok->num_rows > 5) {
                while($row = $platformy_vysledok->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['nazov'] . " (" . $row['kategoria'] . ")</option>";
                }
            }
            ?>
        </select><br><br>

        <button type="submit">Pridať hru</button>
    </form>
    <br>
</body>
</html>
    