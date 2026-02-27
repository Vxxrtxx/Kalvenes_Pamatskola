<?php
$conn = new mysqli("localhost", "root", "", "school_site");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$hero = $conn->query("SELECT * FROM content WHERE id=1")->fetch_assoc();
$aktualitates = $conn->query("SELECT * FROM aktualitates");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <link rel="stylesheet" href="/KalvenesPamataskola/SkolaMainPage/Nav.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <title>Document</title>
</head>
<body>
    <nav class="navbar">
        <button class="menu-toggle">&#9776;</button>
        <div class="nav-wrapper">
        <div class="nav-left">
            <div class="dropdown" id="SchoolDropdown">
                <a href="#" class="hover-underline dropdown-trigger">Par Skolu▼</a>
                <div class="dropdown-content"> <!--Vajag 3 file-->
                    <a href="/skola/ParMumsNav.html">Par mums</a>
                    <a href="/skola/DokumentiNav.html">Dokumentācija</a>
                    <a href="/skola/SasniegumiNav.html">Sasniegumi</a>
                </div>
            </div>
            <div class="dropdown" id="AdmissionDropdown">
                <a href="/Pienemsana/Uznemsana.html" class="hover-underline dropdown-trigger">Uzņemšana</a>
            </div>
            <a href="/Skolasvest/Vesture.html" class="hover-underline">Skolas vēsture</a> <!--Parunāt ar Sk.Vitu-->
            <a href="/SkolaMainPage/Lapa.html" class="hover-underline">🏠︎</a>
        </div>
        <div class="nav-right">
            <a href="/Kontakti/KontaktiMain.html" class="hover-underline">Kontakti</a>
            <a href="https://www.facebook.com/kalvene.pamatskola.9/?locale=lv_LV" target="_blank" class="facebook-icon">
                <i class="fab fa-facebook-square"></i>
            </a>
        </div>
    </div>
    </nav>
<div class="page-content">
    <section class="hero">
    <video autoplay muted loop playsinline class="hero-video">
        <source src="<?= !empty($hero['video_path']) ? $hero['video_path'] : '/videos/skola.mp4' ?>" type="video/mp4">
    </video>

    <div class="hero-content">
        <h1><?= !empty($hero['title']) ? htmlspecialchars($hero['title']) : 'Kalvenes pamatskola' ?></h1>
        <p><?= !empty($hero['subtitle']) ? htmlspecialchars($hero['subtitle']) : 'Mūsu nākotne sākas šeit' ?></p>
    </div>
</section>
    <?php if($aktualitates && $aktualitates->num_rows > 0): ?>
    <?php while($row = $aktualitates->fetch_assoc()): ?>
        <div class="aktualitate-card">
            <img src="<?= $row['image'] ?>">
            <p><?= htmlspecialchars($row['text']) ?></p>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <div class="aktualitate-card">
        <img src="/images/bilde1.jpg">
        <p>Tava oriģinālā aktualitāte</p>
    </div>
<?php endif; ?>

    <section class="timeline-section">
        <h2>Kāpēc izvēlēties Kalvenes pamatskolu?</h2>
        <div class="timeline-container">
            <div class="timeline-card">
                <img src="/SkolaMainPage/SkolasAtteli/Bilde1.jpg" alt="Ak1">
                <h3>Kvalitatīva izglītība</h3>
                <p>Piedāvājam izcilu izglītību ar mūsdienīgiem mācību materiāliem un metodēm</p>
            </div>
            <div class="timeline-card">
                <img src="/SkolaMainPage/SkolasAtteli/Bilde1.jpg" alt="Ak2">
                <h3>Atbalstoša vide</h3>
                <p>Veidojam draudzīgu un drošu vidi katram skolēnam.</p>
            </div>
            <div class="timeline-card">
                <img src="/SkolaMainPage/SkolasAtteli/Bilde1.jpg" alt="Ak3">
                <h3>Izaugsmes iespējas</h3>
                <p>Palīdzam attīstīt katra skolēna talantus un prasmes.</p>
            </div>
        </div>
    </section>
</div>
    <footer class="end-container">
        <p>© 2025 Kalvenes pamatskola. All rights reserved.</p>
    </footer>

    <script src="/SkolaMainPage/script.js"></script>
</body>
</html>
<!--Geogrs Laters Semmers, 2.v anglu val-->
<!--Valters Reinfelds, 1v anglu val-->
<!--Alans Strazds, 2v matematika-->
<!--Kate Jonase, 1.Pak, Skatuves runas konkurss
1. Pak, Melisa Ziobrovska, Ernests Gustavs Birznieks-->
<!--Oskars Kronbergs, pavasara kross 1.v-->
<!--Keita Lagzda, "Starojums" 3.pak kurzemes zona-->