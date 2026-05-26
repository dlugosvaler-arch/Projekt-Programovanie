<?php
require_once 'db.php';

// Načítame všetky platformy a k nim pripojené hry (LEFT JOIN zabezpečí, že uvidíme aj prázdne platformy ako v Illustratori)
$sql = "SELECT platformy.id AS platform_id, platformy.nazov AS platforma_nazov, platformy.kategoria,
               hry.id AS hra_id, hry.nazov AS hra_nazov, hry.zaner, hry.rok_vydania 
        FROM platformy 
        LEFT JOIN hry ON platformy.id = hry.platforma_id 
        ORDER BY platformy.kategoria DESC, platformy.nazov ASC";

$vysledok = $conn->query($sql);

// Preusporiadanie dát v PHP do logických skupín podľa platforiem
$platformy = [];
if ($vysledok && $vysledok->num_rows > 0) {
    while($row = $vysledok->fetch_assoc()) {
        $p_id = $row['platform_id'];
        if (!isset($platformy[$p_id])) {
            $platformy[$p_id] = [
                'nazov' => $row['platforma_nazov'],
                'kategoria' => $row['kategoria'],
                'hry' => []
            ];
        }
        // Ak k platforme existuje hra, pridáme ju do zoznamu
        if ($row['hra_id'] !== null) {
            $platformy[$p_id]['hry'][] = [
                'id' => $row['hra_id'],
                'nazov' => $row['hra_nazov'],
                'zaner' => $row['zaner'],
                'rok' => $row['rok_vydania']
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Gamer Vault - Katalóg Hier</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>Užívateľov katalóg hier</h1>
    
    <a href="pridaj.php" class="btn-add">➕ Pridať novú hru</a>

    <div class="dashboard-grid">
        <?php foreach ($platformy as $id => $data): ?>
            <div class="platform-card">
                <h3><?php echo htmlspecialchars($data['nazov']); ?> <span style="font-size: 11px; color:#8a8a93;">(<?php echo htmlspecialchars($data['kategoria']); ?>)</span></h3>
                
                <ul class="game-list">
                    <?php if (empty($data['hry'])): ?>
                        <li class="game-item" style="color: #4e4e54; font-style: italic;">Žiadne hry</li>
                    <?php else: ?>
                        <?php foreach ($data['hry'] as $hra): ?>
                            <li class="game-item">
                                <div class="game-info">
                                    <strong><?php echo htmlspecialchars($hra['nazov']); ?></strong>
                                    <span class="game-meta"><?php echo htmlspecialchars($hra['zaner']); ?> • <?php echo htmlspecialchars($hra['rok']); ?></span>
                                </div>
                                <a href="zmazat.php?id=<?php echo $hra['id']; ?>" class="btn-delete" onclick="return confirm('Naozaj chceš zmazať túto hru?')">❌</a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>