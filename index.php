<?php
session_start();
require_once 'db.php';

if (isset($_GET['prepni_pouzivatela'])) {
    $_SESSION['user_id'] = intval($_GET['prepni_pouzivatela']);
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
}

$aktualny_pouzivatel_id = $_SESSION['user_id'];

$pouzivatel_query = $conn->query("SELECT meno FROM pouzivatelia WHERE id = $aktualny_pouzivatel_id");
$aktualny_pouzivatel = $pouzivatel_query->fetch_assoc();
$vsetci_pouzivatelia = $conn->query("SELECT * FROM pouzivatelia");

$search_query = "";
$search_value = "";

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search_value = $conn->real_escape_string($_GET['search']);
    $search_query = " AND (hry.nazov LIKE '%$search_value%' OR hry.zaner LIKE '%$search_value%')";
}

$sql = "SELECT platformy.id AS platform_id, platformy.nazov AS platforma_nazov, platformy.kategoria,
               hry.id AS hra_id, hry.nazov AS hra_nazov, hry.zaner, hry.rok_vydania 
        FROM platformy 
        LEFT JOIN hry ON platformy.id = hry.platforma_id AND hry.pouzivatel_id = $aktualny_pouzivatel_id $search_query
        ORDER BY platformy.kategoria DESC, platformy.nazov ASC";

$vysledok = $conn->query($sql);

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

    <div class="header-container">
        <h1>Knižnica hráča: <span style="color: #ff003c;"><?php echo htmlspecialchars($aktualny_pouzivatel['meno']); ?></span></h1>
        
        <div class="profile-switcher">
            <span style="color: #8a8a93; font-size: 14px; text-transform: uppercase;">🎮 Hráč:</span>
            <?php while($p = $vsetci_pouzivatelia->fetch_assoc()): ?>
                <a href="index.php?prepni_pouzivatela=<?php echo $p['id']; ?>" 
                   class="profile-link <?php echo ($p['id'] == $aktualny_pouzivatel_id) ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($p['meno']); ?>
                </a>
            <?php endwhile; ?>
        </div>
    </div>
    
    <a href="pridaj.php" class="btn-add">➕ Pridať novú hru</a>

    <form method="GET" action="index.php" class="search-container">
        <input type="text" name="search" class="search-input" 
               placeholder="Vyhľadaj hru podľa názvu alebo žánru..." 
               value="<?php echo htmlspecialchars($search_value); ?>">
        
        <button type="submit" class="btn-search">Hľadať</button>
        
        <?php if (!empty($search_value)): ?>
            <a href="index.php" class="btn-cancel">Zrušiť</a>
        <?php endif; ?>
    </form>

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