<?php
$conn = @new mysqli("localhost", "root", "", "school_site");
$contacts = $conn && !$conn->connect_error ? $conn->query("SELECT * FROM contacts WHERE id=1")->fetch_assoc() : null;
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontakti</title>
    <link rel="stylesheet" href="/Kalvenes_Pamatskola/SkolaMainPage/Nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Oswald:wght@200..700&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <button class="menu-toggle">&#9776;</button>
        <div class="nav-wrapper">
        <div class="logo">KALVENES</div>
        <div class="nav-left">
            <div class="dropdown" id="SchoolDropdown">
                <a href="#" class="hover-underline dropdown-trigger">Par Skolu▼</a>
                <div class="dropdown-content"> <!--Vajag 3 file-->
                    <a href="/Kalvenes_Pamatskola/skola/ParMumsNav.html">Par mums</a>
                    <a href="/Kalvenes_Pamatskola/skola/DokumentiNav.html">Dokumentācija</a>
                    <a href="/Kalvenes_Pamatskola/skola/SasniegumiNav.html">Sasniegumi</a>
                </div>
            </div>
            <div class="dropdown" id="AdmissionDropdown">
                <a href="/Kalvenes_Pamatskola/Pienemsana/Uznemsana.html" class="hover-underline dropdown-trigger">Uzņemšana</a>
            </div>
            <a href="/Kalvenes_Pamatskola/Skolasvest/Vesture.html" class="hover-underline">Skolas vēsture</a> <!--Parunāt ar Sk.Vitu-->
            <a href="/Kalvenes_Pamatskola/SkolaMainPage/Lapa.php" class="hover-underline">🏠︎</a>
        </div>
        <div class="nav-right">
            <a href="/Kalvenes_Pamatskola/Kontakti/KontaktiMain.php" class="hover-underline">Kontakti</a>
            <a href="https://www.facebook.com/kalvene.pamatskola.9/?locale=lv_LV" target="_blank" class="facebook-icon">
                <i class="fab fa-facebook-square"></i>
            </a>
            <button type="button" class="dark-mode-toggle" aria-label="Toggle dark mode">🌙</button>
        </div>
    </div>
    </nav>
<div class="page-content">
            <div class="contact-container">
                <h1>Sazinieties ar mums</h1>
            <div class="contact-info">
            <div class="contact-item">
                <i class="fas fa-phone"></i>
            <span><?= htmlspecialchars($contacts['phone'] ?? '+371 29577075') ?></span>
        </div>
        <div class="contact-item">
            <i class="fas fa-envelope"></i>
                <span><?= htmlspecialchars($contacts['email'] ?? 'info@kalvene.edu.lv') ?></span>
        </div>
        <div class="contact-item">
            <i class="fas fa-map-marker-alt"></i>
                <a href="<?= htmlspecialchars($contacts['map_link'] ?? 'https://maps.google.com/?q=Kalvenes+pamatskola') ?>" target="_blank" class="map-link">
                    <?= htmlspecialchars($contacts['address'] ?? 'Skolas iela 1, Kalvenes pagasts, Dienvidkurzemes novads, LV-3443') ?>
                </a>
            </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>kalvenes.skola@dkn.lv</span>
                </div>
            </div>
        </div>
    </div>
  <footer>
    © 2025 Kalvenes Pamatskola. Visas tiesības aizsargātas.
  </footer>
    <script src="/Kalvenes_Pamatskola/SkolaMainPage/script.js"></script>
</body>
</html>
