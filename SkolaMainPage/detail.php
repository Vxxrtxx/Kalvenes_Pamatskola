<?php
$conn = @new mysqli("localhost", "root", "", "school_site");
$connOk = !$conn->connect_error;

$project = explode("/", trim($_SERVER["SCRIPT_NAME"], "/"))[0];

function asset_url(?string $path, string $project): string {
    if (!$path) return "";

    $path = trim($path);

    if (str_starts_with($path, "/" . $project . "/")) {
        $final = $path;
    } elseif (str_starts_with($path, "/Control-Panel/")) {
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

$type = $_GET['type'] ?? '';
$slug = $_GET['slug'] ?? '';

$allowed = ['aktualitates', 'timeline'];
if (!in_array($type, $allowed, true) || $slug === '' || !$connOk) {
    http_response_code(404);
    exit('Page not found');
}

if ($type === 'aktualitates') {
    $stmt = $conn->prepare("SELECT * FROM aktualitates WHERE slug=? LIMIT 1");
} else {
    $stmt = $conn->prepare("SELECT * FROM timeline WHERE slug=? LIMIT 1");
}

$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

if (!$item) {
    http_response_code(404);
    exit('Page not found');
}

$title = $item['title'] ?? '';
$short = $type === 'aktualitates' ? ($item['text'] ?? '') : ($item['description'] ?? '');
$details = $item['details'] ?? '';
$image = asset_url($item['image'] ?? '', $project);
$fallbackImage = "/" . $project . "/SkolaMainPage/SkolasAtteli/Bilde1.jpg";
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/<?= htmlspecialchars($project) ?>/SkolaMainPage/Nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        body {
            margin: 0;
            background: #E1DFDF;
            font-family: "Oswald", sans-serif;
            color: #32373B;
        }

        .detail-page {
            max-width: 1100px;
            margin: 50px auto;
            background: #ECDAD2;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .detail-image {
            width: 100%;
            height: 420px;
            object-fit: cover;
            display: block;
        }

        .detail-content {
            padding: 40px;
        }

        .detail-content h1 {
            font-size: 3rem;
            margin: 0 0 15px;
            color: #32373B;
        }

        .detail-lead {
            font-size: 1.3rem;
            margin-bottom: 25px;
        }

        .detail-text {
            font-size: 1.15rem;
            line-height: 1.8;
            white-space: pre-line;
        }

        .back-btn {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 18px;
            background: #32373B;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
        }

        footer {
            background-color: #ECDAD2;
            padding: 20px;
            text-align: center;
            font-size: 1.2em;
            font-weight: 700;
            border-top: 4px solid #32373B;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <button class="menu-toggle">&#9776;</button>
    <div class="nav-wrapper">
        <div class="nav-left">
            <div class="dropdown" id="SchoolDropdown">
                <a href="#" class="hover-underline dropdown-trigger">Par Skolu▼</a>
                <div class="dropdown-content">
                    <a href="/<?= htmlspecialchars($project) ?>/skola/ParMumsNav.html">Par mums</a>
                    <a href="/<?= htmlspecialchars($project) ?>/skola/DokumentiNav.html">Dokumentācija</a>
                    <a href="/<?= htmlspecialchars($project) ?>/skola/SasniegumiNav.html">Sasniegumi</a>
                </div>
            </div>
            <a href="/<?= htmlspecialchars($project) ?>/Pienemsana/Uznemsana.html" class="hover-underline">Uzņemšana</a>
            <a href="/<?= htmlspecialchars($project) ?>/Skolasvest/Vesture.html" class="hover-underline">Skolas vēsture</a>
            <a href="/<?= htmlspecialchars($project) ?>/SkolaMainPage/Lapa.php" class="hover-underline">🏠︎</a>
        </div>
        <div class="nav-right">
            <a href="/<?= htmlspecialchars($project) ?>/Kontakti/KontaktiMain.html" class="hover-underline">Kontakti</a>
            <a href="https://www.facebook.com/kalvene.pamatskola.9/?locale=lv_LV" target="_blank" class="facebook-icon">
                <i class="fab fa-facebook-square"></i>
            </a>
        </div>
    </div>
</nav>

<div class="detail-page">
    <img class="detail-image" src="<?= htmlspecialchars($image ?: $fallbackImage) ?>" alt="<?= htmlspecialchars($title) ?>" onerror="this.onerror=null;this.src='<?= htmlspecialchars($fallbackImage) ?>';">

    <div class="detail-content">
        <h1><?= htmlspecialchars($title) ?></h1>
        <div class="detail-lead"><?= htmlspecialchars($short) ?></div>
        <div class="detail-text"><?= nl2br(htmlspecialchars($details)) ?></div>
        <a class="back-btn" href="/<?= htmlspecialchars($project) ?>/SkolaMainPage/Lapa.php">← Back</a>
    </div>
</div>

<footer>
    © 2025 Kalvenes Pamatskola. Visas tiesības aizsargātas.
</footer>

<script>
document.querySelector(".menu-toggle")?.addEventListener("click", function () {
    document.querySelector(".navbar")?.classList.toggle("expanded");
});
</script>
</body>
</html>