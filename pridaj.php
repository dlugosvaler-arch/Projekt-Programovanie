<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nazov = $conn->real_escape_string($_POST['nazov']);
    $zaner = $conn->real_escape_string($_POST['zaner']);
    $rok = intval($_POST['rok_vydania']);
    $platforma_id = intval($_POST['platforma_id']);
    $pouzivatel_id = 1; 

    if (!empty($nazov) && !empty($platforma_id)) {
        $sql = "INSERT INTO hry (nazov, zaner, rok_vydania, platforma_id, pouzivatel_id) 
                VALUES ('$nazov', '$zaner', '$rok', '$platforma_id', '$pouzivatel_id')";
        
        if ($conn->query($sql) === TRUE) {
            echo "<div style='background-color: #155724; color: #d4edda; padding: 15px; margin-bottom: 20px; border-radius: 4px;'>Hra bola úspešne pridaná! Presmerovávam...</div>";
            header("refresh:1.5; url=index.php");
            exit;
        } else {
            echo "Chyba: " . $conn->error;
        }
    }
}

$platformy_vysledok = $conn->query("SELECT * FROM platformy ORDER BY kategoria DESC, nazov ASC");
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Pridať hru - Gamer Vault</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>Pridať novú hru</h1>

    <div class="form-container">
        <form method="POST" action="pridaj.php">
            <div class="form-group">
                <label>Názov hry *</label>
                <input type="text" name="nazov" class="form-control" required placeholder="Napr. GTA V">
            </div>

            <div class="form-group">
                <label>Žáner</label>
                <select name="zaner" class="form-control">
                    <option value="">-- Vyberte žáner --</option>
                    <option value="Akčná">Akčná</option>
                    <option value="Adventúra">Adventúra</option>
                    <option value="RPG">RPG</option>
                    <option value="Športová">Športová</option>
                    <option value="Simulátor">Simulátor</option>
                    <option value="Stratégia">Stratégia</option>
                    <option value="Horor">Horor</option>
                </select>
            </div>

            <div class="form-group">
                <label>Rok vydania</label>
                <input type="number" name="rok_vydania" class="form-control" placeholder="Napr. 2013">
            </div>

            <div class="form-group">
                <label>Platforma / Klient *</label>
                <select name="platforma_id" class="form-control" required>
                    <option value="">-- Vyberte platformu --</option>
                    <?php
                    if ($platformy_vysledok->num_rows > 0) {
                        while($row = $platformy_vysledok->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['nazov'] . " (" . $row['kategoria'] . ")</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <button type="submit" class="btn-submit">Uložiť hru do knižnice</button>
        </form>
        
        <br>
        <a href="index.php" style="color: #8a8a93; text-decoration: none; font-size: 14px;">⬅ Späť na domovskú obrazovku</a>
    </div>

</body>
</html>