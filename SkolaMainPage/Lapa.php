<?php
$conn = @new mysqli("localhost", "root", "", "school_site");
$connOk = !$conn->connect_error;

$hero = $connOk ? $conn->query("SELECT * FROM content WHERE id=1")->fetch_assoc() : null;
$aktualitates = $connOk ? $conn->query("SELECT * FROM aktualitates ORDER BY id DESC LIMIT 3") : null;
$timeline = $connOk ? $conn->query("SELECT * FROM timeline ORDER BY id DESC LIMIT 3") : null;

function asset_url(?string $path): string {
    if (!$path) return "";

    $path = trim($path);

    if (str_starts_with($path, "/admin/")) {
        $path = "/KalvenesPamataskola" . $path;
    } elseif (str_starts_with($path, "/SkolaMainPage/")) {
        $path = "/KalvenesPamataskola" . $path;
    } elseif (!str_starts_with($path, "/KalvenesPamataskola/") && str_starts_with($path, "/")) {
        $path = "/KalvenesPamataskola" . $path;
    }

    $parts = explode("/", $path);
    $parts = array_map(function($p) { return rawurlencode($p); }, $parts);
    return implode("/", $parts);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <link rel="stylesheet" href="/KalvenesPamataskola/SkolaMainPage/Nav.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
                <div class="dropdown-content">
                    <a href="/KalvenesPamataskola/skola/ParMumsNav.html">Par mums</a>
                    <a href="/KalvenesPamataskola/skola/DokumentiNav.html">Dokumentācija</a>
                    <a href="/KalvenesPamataskola/skola/SasniegumiNav.html">Sasniegumi</a>
                </div>
            </div>

            <div class="dropdown" id="AdmissionDropdown">
                <a href="/KalvenesPamataskola/Pienemsana/Uznemsana.html" class="hover-underline dropdown-trigger">Uzņemšana</a>
            </div>

            <a href="/KalvenesPamataskola/Skolasvest/Vesture.html" class="hover-underline">Skolas vēsture</a>

            <a href="/KalvenesPamataskola/SkolaMainPage/Lapa.php" class="hover-underline">🏠︎</a>
        </div>

        <div class="nav-right">
            <a href="/KalvenesPamataskola/Kontakti/KontaktiMain.html" class="hover-underline">Kontakti</a>
            <a href="https://www.facebook.com/kalvene.pamatskola.9/?locale=lv_LV" target="_blank" class="facebook-icon">
                <i class="fab fa-facebook-square"></i>
            </a>
        </div>
    </div>
</nav>

<div class="page-content">

<!-- HERO -->
<section class="hero">
    <video autoplay muted loop playsinline class="hero-video">
        <source src="<?= !empty($hero['video_path']) ? htmlspecialchars($hero['video_path']) : '/KalvenesPamataskola/SkolaMainPage/SkolasAtteli/Kalvenes skola video3.mp4' ?>" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="hero-content">
        <h1><?= !empty($hero['title']) ? htmlspecialchars($hero['title']) : 'Kalvenes pamatskola' ?></h1>
        <p><?= !empty($hero['subtitle']) ? htmlspecialchars($hero['subtitle']) : 'Izglītība nākotnei' ?></p>
    </div>
</section>

<!-- AKTUALITĀTES -->
<section class="aktualitates">
    <h2>Aktualitātes</h2>
    <div class="aktualitates-container">

        <?php if ($aktualitates && $aktualitates->num_rows > 0): ?>
            <?php while($row = $aktualitates->fetch_assoc()): ?>
                <div class="aktualitate-card">
                    <img src="<?= htmlspecialchars($row['image']) ?>" alt="Aktualitāte">
                    <p><?= htmlspecialchars($row['text']) ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <!-- Fallback (your original static cards) -->
            <div class="aktualitate-card">
                <img src="/KalvenesPamataskola/SkolaMainPage/SkolasAtteli/Bilde1.jpg" alt="Aktualitāte 1">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
            <div class="aktualitate-card">
                <img src="/KalvenesPamataskola/SkolaMainPage/SkolasAtteli/Bilde1.jpg" alt="Aktualitāte 2">
                <p>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae.</p>
            </div>
            <div class="aktualitate-card">
                <img src="/KalvenesPamataskola/SkolaMainPage/SkolasAtteli/Bilde1.jpg" alt="Aktualitāte 3">
                <p>Integer vitae sem dapibus, facilisis lorem ac, finibus ligula.</p>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- TIMELINE -->
<section class="timeline-section">
    <h2>Kāpēc izvēlēties Kalvenes pamatskolu?</h2>
    <div class="timeline-container">

        <?php if ($timeline && $timeline->num_rows > 0): ?>
            <?php while($row = $timeline->fetch_assoc()): ?>
                <div class="timeline-card">
                    <img src="<?= htmlspecialchars($row['image']) ?>" alt="Timeline">
                    <div class="timeline-text">
                        <h3><?= htmlspecialchars($row['title']) ?></h3>
                        <p><?= htmlspecialchars($row['description']) ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <!-- Fallback (your original static cards, including timeline-text wrapper for CSS) -->
            <div class="timeline-card">
                <img src="/KalvenesPamataskola/SkolaMainPage/SkolasAtteli/Bilde1.jpg" alt="Ak1">
                <div class="timeline-text">
                    <h3>Kvalitatīva izglītība</h3>
                    <p>Piedāvājam izcilu izglītību ar mūsdienīgiem mācību materiāliem un metodēm</p>
                </div>
            </div>

            <div class="timeline-card">
                <img src="/KalvenesPamataskola/SkolaMainPage/SkolasAtteli/Bilde1.jpg" alt="Ak2">
                <div class="timeline-text">
                    <h3>Atbalstoša vide</h3>
                    <p>Veidojam draudzīgu un drošu vidi katram skolēnam.</p>
                </div>
            </div>

            <div class="timeline-card">
                <img src="/KalvenesPamataskola/SkolaMainPage/SkolasAtteli/Bilde1.jpg" alt="Ak3">
                <div class="timeline-text">
                    <h3>Izaugsmes iespējas</h3>
                    <p>Palīdzam attīstīt katra skolēna talantus un prasmes.</p>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>

</div>

<footer class="end-container">
    <p>© 2025 Kalvenes pamatskola. All rights reserved.</p>
</footer>

<script src="/KalvenesPamataskola/SkolaMainPage/script.js"></script>
</body>
</html>