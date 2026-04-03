<?php
$conn = @new mysqli("localhost", "root", "", "school_site");
$connOk = !$conn->connect_error;

$hero = $connOk ? $conn->query("SELECT * FROM content WHERE id=1")->fetch_assoc() : null;
$aktualitates = $connOk ? $conn->query("SELECT * FROM aktualitates ORDER BY id DESC LIMIT 3") : null;
$timeline = $connOk ? $conn->query("SELECT * FROM timeline ORDER BY id DESC LIMIT 3") : null;
$project = explode("/", trim($_SERVER["SCRIPT_NAME"], "/"))[0];

function asset_url(?string $path, string $project): string {
    if (!$path) return "";

    $path = trim($path);

    if (str_starts_with($path, "/" . $project . "/")) {
        $final = $path;
    } elseif (str_starts_with($path, "/Control-Panel/")) {
        $final = "/" . $project . $path;
    } elseif (str_starts_with($path, "/admin/")) {
        $final = "/" . $project . $path;
    } elseif (str_starts_with($path, "/SkolaMainPage/")) {
        $final = "/" . $project . $path;
    } elseif (str_starts_with($path, "/")) {
        $final = "/" . $project . $path;
    } else {
        $final = "/" . $project . "/" . ltrim($path, "/");
    }

    $parts = explode("/", $final);
    $parts = array_map(fn($p) => rawurlencode($p), $parts);
    return implode("/", $parts);
}

$fallbackImage = "/" . $project . "/SkolaMainPage/SkolasAtteli/Bilde1.jpg";
$fallbackVideo = "/" . $project . "/SkolaMainPage/SkolasAtteli/Kalvenes skola video3.mp4";
$heroTitle = !empty($hero['title']) ? $hero['title'] : 'Kalvenes Pamatskola';
$heroTitle = preg_replace('/\\bpamatskola\\b/ui', 'Pamatskola', $heroTitle);
$heroSubtitle = !empty($hero['subtitle']) ? $hero['subtitle'] : 'Izglītība nākotnei';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <link rel="stylesheet" href="/<?= htmlspecialchars($project) ?>/SkolaMainPage/Nav.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@600;700;800&family=Manrope:wght@500;600&display=swap" rel="stylesheet">
    <title>Document</title>
</head>
<body data-enable-opening="1">

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

            <div class="dropdown" id="AdmissionDropdown">
                <a href="/<?= htmlspecialchars($project) ?>/Pienemsana/Uznemsana.php" class="hover-underline dropdown-trigger">Uzņemšana</a>
            </div>

            <a href="/<?= htmlspecialchars($project) ?>/Skolasvest/Vesture.php" class="hover-underline">Skolas vēsture</a>
            <a href="/<?= htmlspecialchars($project) ?>/SkolaMainPage/Lapa.php" class="hover-underline home-page-link">🏠︎</a>
        </div>

        <div class="nav-right">
            <a href="/<?= htmlspecialchars($project) ?>/Kontakti/KontaktiMain.php" class="hover-underline">Kontakti</a>
            <a href="https://www.facebook.com/kalvene.pamatskola.9/?locale=lv_LV" target="_blank" class="facebook-icon">
                <i class="fab fa-facebook-square"></i>
            </a>
            <button type="button" class="dark-mode-toggle" aria-label="Toggle dark mode">🌙</button>
        </div>
    </div>
</nav>

<div class="page-content">

<section class="hero">
    <video autoplay muted loop playsinline class="hero-video">
        <source src="<?= asset_url($hero['video_path'] ?? $fallbackVideo, $project) ?>" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="hero-content">
        <h1><?= htmlspecialchars($heroTitle) ?></h1>
        <p><?= htmlspecialchars($heroSubtitle) ?></p>
    </div>
</section>

<section class="aktualitates">
    <div class="section-heading">
        <h2>Aktualitātes</h2>
        <p class="section-intro">Svarīgākie notikumi, paziņojumi un skolas ikdienas jaunumi vienuviet.</p>
    </div>
    <div class="aktualitates-container">
        <?php if ($aktualitates && $aktualitates->num_rows > 0): ?>
            <?php while($row = $aktualitates->fetch_assoc()): ?>
                <div class="aktualitate-card">
                    <img
                        src="<?= asset_url($row['image'] ?? '', $project) ?>"
                        alt="Aktualitāte"
                        onerror="this.onerror=null;this.src='<?= htmlspecialchars($fallbackImage) ?>';"
                    >
                    <div class="card-body">
                        <div class="card-copy">
                            <h3><?= htmlspecialchars($row['title'] ?? 'Nosaukums nav'); ?></h3>
                            <p><?= htmlspecialchars($row['text'] ?? 'Apraksts nav'); ?></p>
                        </div>
                        <a class="more-info-btn" href="/<?= htmlspecialchars($project) ?>/SkolaMainPage/detail.php?type=aktualitates&slug=<?= urlencode($row['slug'] ?: 'aktualitate-1') ?>">Vairāk informācija</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="aktualitate-card">
                <img src="<?= htmlspecialchars($fallbackImage) ?>" alt="Aktualitāte 1">
                <div class="card-body">
                    <div class="card-copy">
                        <h3>Ziema</h3>
                        <p>Mūsdienīgs notikums par sezonālo skolas dzīvi.</p>
                    </div>
                    <a class="more-info-btn" href="/<?= htmlspecialchars($project) ?>/SkolaMainPage/detail.php?type=aktualitates&slug=ziema">Vairāk informācija</a>
                </div>
            </div>
            <div class="aktualitate-card">
                <img src="<?= htmlspecialchars($fallbackImage) ?>" alt="Aktualitāte 2">
                <div class="card-body">
                    <div class="card-copy">
                        <h3>Izglītība</h3>
                        <p>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae.</p>
                    </div>
                    <a class="more-info-btn" href="#">Vairāk informācija</a>
                </div>
            </div>
            <div class="aktualitate-card">
                <img src="<?= htmlspecialchars($fallbackImage) ?>" alt="Aktualitāte 3">
                <div class="card-body">
                    <div class="card-copy">
                        <h3>Kalvenes skola</h3>
                        <p>Integer vitae sem dapibus, facilisis lorem ac, finibus ligula.</p>
                    </div>
                    <a class="more-info-btn" href="#">Vairāk informācija</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="timeline-section">
    <div class="section-heading">
        <h2>Kāpēc izvēlēties Kalvenes pamatskolu?</h2>
        <p class="section-intro">Galvenie iemesli, kāpēc mūsu skola ir droša, attīstoša un atbalstoša vide bērna izaugsmei.</p>
    </div>
    <div class="timeline-container">
        <?php if ($timeline && $timeline->num_rows > 0): ?>
            <?php while($row = $timeline->fetch_assoc()): ?>
                <div class="timeline-card">
                    <img
                        src="<?= asset_url($row['image'] ?? '', $project) ?>"
                        alt="Timeline"
                        onerror="this.onerror=null;this.src='<?= htmlspecialchars($fallbackImage) ?>';"
                    >
                    <div class="timeline-text">
                        <div class="card-copy">
                            <h3><?= htmlspecialchars($row['title'] ?: 'Nav nosaukuma') ?></h3>
                            <p><?= htmlspecialchars($row['description'] ?: 'Nav apraksta') ?></p>
                        </div>
                        <a class="more-info-btn" href="/<?= htmlspecialchars($project) ?>/SkolaMainPage/detail.php?type=timeline&slug=<?= urlencode($row['slug'] ?: 'izaugsme') ?>">Vairāk informācija</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="timeline-card">
                <img src="<?= htmlspecialchars($fallbackImage) ?>" alt="Ak1">
                <div class="timeline-text">
                    <div class="card-copy">
                        <h3>Kvalitatīva izglītība</h3>
                        <p>Piedāvājam izcilu izglītību ar mūsdienīgiem mācību materiāliem un metodēm.</p>
                    </div>
                    <a class="more-info-btn" href="/<?= htmlspecialchars($project) ?>/SkolaMainPage/detail.php?type=timeline&slug=kvalitativa-izglitiba">Vairāk informācija</a>
                </div>
            </div>

            <div class="timeline-card">
                <img src="<?= htmlspecialchars($fallbackImage) ?>" alt="Ak2">
                <div class="timeline-text">
                    <div class="card-copy">
                        <h3>Atbalstoša vide</h3>
                        <p>Veidojam draudzīgu un drošu vidi katram skolēnam.</p>
                    </div>
                    <a class="more-info-btn" href="/<?= htmlspecialchars($project) ?>/SkolaMainPage/detail.php?type=timeline&slug=atbalstosa-vide">Vairāk informācija</a>
                </div>
            </div>

            <div class="timeline-card">
                <img src="<?= htmlspecialchars($fallbackImage) ?>" alt="Ak3">
                <div class="timeline-text">
                    <div class="card-copy">
                        <h3>Izaugsmes iespējas</h3>
                        <p>Palīdzam attīstīt katra skolēna talantus un prasmes.</p>
                    </div>
                    <a class="more-info-btn" href="/<?= htmlspecialchars($project) ?>/SkolaMainPage/detail.php?type=timeline&slug=izaugsmes-iespejas">Vairāk informācija</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

</div>

<footer class="end-container">
    <p>© 2025 Kalvenes pamatskola. All rights reserved.</p>
</footer>

<script src="/<?= htmlspecialchars($project) ?>/SkolaMainPage/script.js?v=20260403"></script>

</body>
</html>
