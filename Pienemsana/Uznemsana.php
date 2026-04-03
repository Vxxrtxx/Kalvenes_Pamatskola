<?php
$conn = @new mysqli("localhost", "root", "", "school_site");
$admission = null;
$contacts = null;

if ($conn && !$conn->connect_error) {
    $result = $conn->query("SELECT * FROM admissions ORDER BY id DESC LIMIT 1");
    $admission = $result ? $result->fetch_assoc() : null;

    $contactResult = $conn->query("SELECT * FROM contacts WHERE id=1");
    $contacts = $contactResult ? $contactResult->fetch_assoc() : null;
}

$project = explode("/", trim($_SERVER["SCRIPT_NAME"], "/"))[0];
$title = $admission['title'] ?? 'Uzņemšana skolā';
$content = $admission['content'] ?? 'Informācija par uzņemšanu Kalvenes pamatskolā.';
$requirements = $admission['requirements'] ?? 'Nepieciešamie dokumenti: dzimšanas apliecība; medicīniskā kartiņa; vecāku iesniegums.';

$phoneDigits = preg_replace('/[^0-9]/', '', $contacts['phone'] ?? '29577075');
$phoneDisplay = '+371 ' . substr($phoneDigits, -8);
$emailDisplay = $contacts['email'] ?? 'info@kalvene.edu.lv';
$addressDisplay = $contacts['address'] ?? 'Skolas iela 1, Kalvenes pagasts, Dienvidkurzemes novads, LV-3443';
$mapLink = $contacts['map_link'] ?? 'https://maps.google.com/?q=Kalvenes+pamatskola';

$reqItems = preg_split('/\r\n|\r|\n|;/', $requirements);
$reqItems = array_values(array_filter(array_map('trim', $reqItems)));
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/<?= htmlspecialchars($project) ?>/SkolaMainPage/Nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Uzņemšana</title>
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
    <section class="page-hero">
        <h1><?= htmlspecialchars($title) ?></h1>
        <p><?= htmlspecialchars($content) ?></p>
    </section>

    <div class="main-container">
        <article class="left-content">
            <h2>Nepieciešamie dokumenti</h2>
            <ul>
                <?php foreach ($reqItems as $item): ?>
                    <li><?= htmlspecialchars($item) ?></li>
                <?php endforeach; ?>
            </ul>

            <h2>Veidlapas</h2>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a class="download-button" href="/<?= htmlspecialchars($project) ?>/Pienemsana/Pamatskola_iesniegums.docx" download>Pamatizglītības programma</a>
                <a class="download-button" href="/<?= htmlspecialchars($project) ?>/Pienemsana/Pirmskola_Iesniegums.docx" download>Pirmsskolas programma</a>
            </div>
        </article>

        <aside class="right-info">
            <h2>Kontakti uzņemšanai</h2>
            <p><strong>Tālrunis:</strong> <?= htmlspecialchars($phoneDisplay) ?></p>
            <p><strong>E-pasts:</strong> <?= htmlspecialchars($emailDisplay) ?></p>
            <p><strong>Adrese:</strong> <?= htmlspecialchars($addressDisplay) ?></p>
            <div class="map-button">
                <i class="fa-solid fa-location-dot"></i>
                <a href="<?= htmlspecialchars($mapLink) ?>" target="_blank" rel="noopener noreferrer">Skatīt kartē ></a>
            </div>
        </aside>
    </div>
</div>

<footer class="end-container">
    <p>© 2025 Kalvenes Pamatskola. Visas tiesības aizsargātas.</p>
</footer>
<script src="/<?= htmlspecialchars($project) ?>/SkolaMainPage/script.js?v=20260403"></script>
</body>
</html>
