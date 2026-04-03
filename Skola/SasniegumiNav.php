<?php
$conn = @new mysqli("localhost", "root", "", "school_site");
$achievements = [];

if ($conn && !$conn->connect_error) {
    $tableExists = $conn->query("SHOW TABLES LIKE 'achievements'");
    if ($tableExists && $tableExists->num_rows > 0) {
        $result = $conn->query("SELECT title, description, icon FROM achievements ORDER BY sort_order ASC, id DESC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $achievements[] = $row;
            }
        }
    }
}

if (count($achievements) === 0) {
    $achievements = [
        ['title' => '2. vieta matemātikā', 'description' => 'Skolēns Alans Strazds ieguvis 2. vietu matemātikas olimpiādē.', 'icon' => 'fa-brain'],
        ['title' => 'Mākslas konkurss', 'description' => 'Keitas Lagzdas mākslas darbs ieguvis 3. pakāpi Kurzemes zonā.', 'icon' => 'fa-paint-brush'],
        ['title' => 'Pavasara kross', 'description' => 'Oskars Kronbergs ieguva 1. vietu pavasara krosā.', 'icon' => 'fa-running'],
        ['title' => 'Runas konkurss', 'description' => 'Skolēni ieguvuši 1. pakāpi skatuves runas konkursā.', 'icon' => 'fa-microphone']
    ];
}

$project = explode("/", trim($_SERVER["SCRIPT_NAME"], "/"))[0];
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/<?= htmlspecialchars($project) ?>/SkolaMainPage/Nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Sasniegumi</title>
</head>
<body>
<nav class="navbar">
    <button class="menu-toggle">&#9776;</button>
    <div class="nav-wrapper">
        <div class="logo">KALVENES PAMATSKOLA</div>
        <div class="nav-left">
            <div class="dropdown" id="SchoolDropdown">
                <a href="#" class="hover-underline dropdown-trigger">Par Skolu▼</a>
                <div class="dropdown-content">
                    <a href="/<?= htmlspecialchars($project) ?>/skola/ParMumsNav.html">Par mums</a>
                    <a href="/<?= htmlspecialchars($project) ?>/skola/DokumentiNav.html">Dokumentācija</a>
                    <a href="/<?= htmlspecialchars($project) ?>/skola/SasniegumiNav.php">Sasniegumi</a>
                </div>
            </div>
            <a href="/<?= htmlspecialchars($project) ?>/Pienemsana/Uznemsana.php" class="hover-underline">Uzņemšana</a>
            <a href="/<?= htmlspecialchars($project) ?>/Skolasvest/Vesture.php" class="hover-underline">Skolas vēsture</a>
            <a href="/<?= htmlspecialchars($project) ?>/SkolaMainPage/Lapa.php" class="hover-underline home-page-link">🏠︎</a>
        </div>
        <div class="nav-right">
            <a href="/<?= htmlspecialchars($project) ?>/Kontakti/KontaktiMain.php" class="hover-underline">Kontakti</a>
            <a href="https://www.facebook.com/kalvene.pamatskola.9/?locale=lv_LV" target="_blank" class="facebook-icon"><i class="fab fa-facebook-square"></i></a>
            <button type="button" class="dark-mode-toggle" aria-label="Toggle dark mode">🌙</button>
        </div>
    </div>
</nav>

<div class="page-content">
    <header class="achievements-hero">
        <h1>Sasniegumi</h1>
        <p>Mūsu skolēnu izcilākie sasniegumi mācībās, sportā un radošajos projektos.</p>
    </header>

    <section class="achievements-grid">
        <?php foreach ($achievements as $item): ?>
            <article class="achievement-card">
                <h2><i class="fas <?= htmlspecialchars($item['icon'] ?: 'fa-award') ?>"></i> <?= htmlspecialchars($item['title'] ?? 'Sasniegums') ?></h2>
                <p><?= htmlspecialchars($item['description'] ?? '') ?></p>
            </article>
        <?php endforeach; ?>
    </section>
</div>

<footer class="end-container">
    <p>© 2025 Kalvenes Pamatskola. Visas tiesības aizsargātas.</p>
</footer>
<script src="/<?= htmlspecialchars($project) ?>/SkolaMainPage/script.js?v=20260403"></script>
</body>
</html>
