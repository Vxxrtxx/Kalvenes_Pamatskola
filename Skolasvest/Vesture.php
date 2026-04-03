<?php
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
    <div class="history-container">
        <img src="/<?= htmlspecialchars($project) ?>/SkolaMainPage/SkolasAtteli/Bilde3.jpg" alt="Tāšu Padures muiža">
        <div class="history-text">
            <h2>Padures muižas un Kalvenes skolas vēsture</h2>

            <p><strong>Padures muiža</strong> (vācu: Paddern) ir viena no ievērojamākajām klasicisma stila muižām Latvijā.</p>

            <p>Muiža izveidojās jau 18. gadsimtā, bet tās pašreizējā pils celta ap 19. gadsimta sākumu, ap 1820. gadu. To būvēja baronu fon Firksu (von Fircks) dzimta, kas bija ietekmīga Kurzemes muižniecībā. Ēka izceļas ar klasicisma arhitektūru, simetrisku fasādi, kolonnām un atturīgu eleganci. Muiža bija ne tikai dzīvojamā ēka, bet arī saimniecības centrs ar plašu parku un lauksaimniecības teritorijām.</p>

            <p><strong>20. gadsimts:</strong> Pēc Latvijas agrārreformas 1920. gados muiža tika nacionalizēta. Tajā dažādos laikos atradās skola, kultūras iestādes un dzīvokļi. Padomju laikā ēka tika izmantota praktiskiem mērķiem, bet daļēji zaudēja sākotnējo greznību.</p>

            <p><strong>Mūsdienās:</strong> Muiža ir atjaunota un saglabāta kā kultūrvēsturisks objekts. Tajā notiek pasākumi, ekskursijas un kultūras aktivitātes.</p>

            <p><strong>Kalvenes pamatskola</strong> ir cieši saistīta ar vietējās kopienas attīstību.</p>

            <p>Izglītība Kalvenē aizsākās jau 19. gadsimtā, kad lauku teritorijās sāka veidoties pirmās tautskolas. Sākotnēji skola darbojās vienkāršās ēkās un nodrošināja pamatizglītību vietējiem bērniem.</p>

            <p><strong>20. gadsimts:</strong> Latvijas brīvvalsts laikā no 1918. līdz 1940. gadam skolu sistēma tika sakārtota, un Kalvenē izveidojās stabila pamatskola. Padomju periodā skola tika paplašināta, uzlabota infrastruktūra un mācību iespējas.</p>

            <p><strong>Mūsdienās:</strong> Kalvenes pamatskola turpina darboties kā vietējās izglītības centrs, nodrošinot pamatizglītību. Skola ir nozīmīga ne tikai izglītībā, bet arī kultūras un sabiedriskajā dzīvē Kalvenē.</p>
        </div>
    </div>
</div>

<footer class="end-container">
    <p>© 2025 Kalvenes Pamatskola. Visas tiesības aizsargātas.</p>
</footer>
<script src="/<?= htmlspecialchars($project) ?>/SkolaMainPage/script.js?v=20260403"></script>
</body>
</html>
