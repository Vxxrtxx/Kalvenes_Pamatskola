<?php
$conn = @new mysqli("localhost", "root", "", "school_site");
$historyRows = [];

if ($conn && !$conn->connect_error) {
    $result = $conn->query("SELECT year, title, content FROM school_history ORDER BY year ASC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $historyRows[] = $row;
        }
    }
}

if (count($historyRows) === 0) {
    $historyRows = [
        ['year' => 1700, 'title' => '18. gadsimta sākums', 'content' => 'Tāšu Padures muiža izveidojās kā nozīmīgs lauku īpašums Kurzemē un bija cieši saistīta ar apkārtējo saimniecību dzīvi.'],
        ['year' => 1800, 'title' => '19. gadsimts', 'content' => 'Muiža piedzīvoja attīstību un modernizāciju, tika paplašināti saimniecības kompleksi un ainavu parki.'],
        ['year' => 1900, 'title' => '20. gadsimts', 'content' => 'Pēc agrārās reformas muižas zeme tika sadalīta jaunsaimniekiem, bet ēkas ieguva jaunas funkcijas un vēlāk kļuva par skolu.'],
        ['year' => 1922, 'title' => 'Skolas dibināšana', 'content' => 'Kalvenes pamatskola dibināta 1922. gadā muižas ēkā, saglabājot vēsturiskās arhitektūras vērtības.']
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
    <title>Skolas vēsture</title>
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
            <a href="/<?= htmlspecialchars($project) ?>/SkolaMainPage/Lapa.php" class="hover-underline">🏠︎</a>
        </div>
        <div class="nav-right">
            <a href="/<?= htmlspecialchars($project) ?>/Kontakti/KontaktiMain.php" class="hover-underline">Kontakti</a>
            <a href="https://www.facebook.com/kalvene.pamatskola.9/?locale=lv_LV" target="_blank" class="facebook-icon"><i class="fab fa-facebook-square"></i></a>
            <button type="button" class="dark-mode-toggle" aria-label="Toggle dark mode">🌙</button>
        </div>
    </div>
</nav>

<div class="page-content">
    <div class="history-container">
        <img src="/<?= htmlspecialchars($project) ?>/SkolaMainPage/SkolasAtteli/Bilde3.jpg" alt="Tāšu Padures muiža">
        <div class="history-text">
            <h2>Tāšu Padures muižas un Kalvenes skolas vēsture</h2>
            <?php foreach ($historyRows as $row): ?>
                <p><strong><?= htmlspecialchars((string)$row['year']) ?> - <?= htmlspecialchars($row['title'] ?? 'Vēstures notikums') ?>:</strong> <?= htmlspecialchars($row['content'] ?? '') ?></p>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<footer class="end-container">
    <p>© 2025 Kalvenes Pamatskola. Visas tiesības aizsargātas.</p>
</footer>
<script src="/<?= htmlspecialchars($project) ?>/SkolaMainPage/script.js"></script>
</body>
</html>
