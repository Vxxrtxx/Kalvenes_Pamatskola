<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  

    <!-- FIXED CSS PATH -->
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

            <!-- FIXED HOME LINK -->
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

<section class="hero">
    <video autoplay muted loop playsinline class="hero-video">
        <source src="/KalvenesPamataskola/SkolaMainPage/SkolasAtteli/Kalvenes skola video3.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="hero-content">
        <h1>Kalvenes pamatskola</h1>
        <p>Izglītība nākotnei</p>
    </div>
</section>

<section class="aktualitates">
    <h2>Aktualitātes</h2>
    <div class="aktualitates-container">
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
    </div>
</section>

<section class="timeline-section">
    <h2>Kāpēc izvēlēties Kalvenes pamatskolu?</h2>
    <div class="timeline-container">
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
    </div>
</section>

</div>

<footer class="end-container">
    <p>© 2025 Kalvenes pamatskola. All rights reserved.</p>
</footer>

<script src="/KalvenesPamataskola/SkolaMainPage/script.js"></script>
</body>
</html>