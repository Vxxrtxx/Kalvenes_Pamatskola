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

if (!$item && $type === 'timeline') {
    $fallbackTimelineDetails = [
        'kvalitativa-izglitiba' => [
            'title' => 'Kvalitatīva izglītība',
            'short' => 'Piedāvājam izcilu izglītību ar mūsdienīgiem mācību materiāliem un metodēm.',
            'details' => "Mūsu mērķis ir nodrošināt skolēniem stabilu pamatu tālākām mācībām, attīstot zināšanas, prasmes un pārliecību par sevi.",
        ],
        'atbalstosa-vide' => [
            'title' => 'Atbalstoša vide',
            'short' => 'Veidojam draudzīgu un drošu vidi katram skolēnam.',
            'details' => "Skolā svarīga ir cieņa, drošība un savstarpējs atbalsts. Mēs rūpējamies, lai ikviens skolēns justos pieņemts un motivēts augt.",
        ],
        'izaugsmes-iespejas' => [
            'title' => 'Izaugsmes iespējas',
            'short' => 'Palīdzam attīstīt katra skolēna talantus un prasmes.',
            'details' => "Mēs atbalstām skolēnu intereses, piedāvājam iespējas piedalīties dažādās aktivitātēs un palīdzam attīstīt individualitāti.",
        ],
    ];

    if (isset($fallbackTimelineDetails[$slug])) {
        $fallback = $fallbackTimelineDetails[$slug];
        $title = $fallback['title'];
        $short = $fallback['short'];
        $details = $fallback['details'];
        $image = asset_url('/SkolaMainPage/SkolasAtteli/Bilde1.jpg', $project);
        $item = true;
    }
}

if (!$item) {
    // Keep the page user-friendly when slug is not found.
    $title = 'Aktualitāte nav atrasta';
    $short = 'Iespējams, izmantojāt nepareizu saiti. Lūdzu, atgriezieties sākumlapā un mēģiniet vēlreiz.';
    $details = 'Šī aktualitāte vēl nav pievienota vai ir izdzēsta. Lūdzu, sazinieties ar administrāciju, lai iegūtu jaunāko informāciju.';
    $image = asset_url('/SkolaMainPage/SkolasAtteli/Bilde1.jpg', $project);
} else {
    $title = $item['title'] ?? '';
    $short = $type === 'aktualitates' ? ($item['text'] ?? '') : ($item['description'] ?? '');
    $details = $item['details'] ?? ($item['text'] ?? 'Papildu informācija tiks pievienota drīzumā.');
    $image = asset_url($item['image'] ?? '', $project);
}

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
            background:
                linear-gradient(130deg, rgba(0, 0, 0, 0.78) 0%, rgba(0, 0, 0, 0.78) 100%),
                var(--bg-primary);
            color: var(--text-primary);
        }

        .detail-page {
            max-width: 1120px;
            margin: 0 auto;
            background: var(--bg-glass);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: var(--shadow-xl);
        }

        .detail-image {
            width: 100%;
            height: 430px;
            object-fit: cover;
            display: block;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }

        .detail-content {
            padding: 2.2rem 2rem 2.4rem;
        }

        .detail-content h1 {
            font-size: clamp(2.2rem, 5vw, 3.3rem);
            margin: 0 0 1rem;
            color: var(--text-primary);
            line-height: 1.15;
        }

        .detail-lead {
            font-size: 1.2rem;
            margin-bottom: 1.3rem;
            color: var(--text-secondary);
            font-weight: 600;
        }

        .detail-text {
            font-size: 1.05rem;
            line-height: 1.8;
            white-space: pre-line;
            color: var(--text-secondary);
        }

        .back-btn {
            display: inline-block;
            margin-top: 1.8rem;
            padding: 0.75rem 1.2rem;
            background: rgba(255, 255, 255, 0.12);
            color: var(--text-primary);
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-decoration: none;
            border-radius: var(--radius-md);
            font-weight: 700;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.32);
        }

        @media (max-width: 768px) {
            .detail-image {
                height: 260px;
            }

            .detail-content {
                padding: 1.4rem 1.1rem 1.6rem;
            }
        }
    </style>
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
            <a href="https://www.facebook.com/kalvene.pamatskola.9/?locale=lv_LV" target="_blank" class="facebook-icon">
                <i class="fab fa-facebook-square"></i>
            </a>
            <button type="button" class="dark-mode-toggle" aria-label="Toggle dark mode">🌙</button>
        </div>
    </div>
</nav>

<div class="page-content">
    <div class="detail-page">
        <img class="detail-image" src="<?= htmlspecialchars($image ?: $fallbackImage) ?>" alt="<?= htmlspecialchars($title) ?>" onerror="this.onerror=null;this.src='<?= htmlspecialchars($fallbackImage) ?>';">

        <div class="detail-content">
            <h1><?= htmlspecialchars($title) ?></h1>
            <div class="detail-lead"><?= htmlspecialchars($short) ?></div>
            <div class="detail-text"><?= nl2br(htmlspecialchars($details)) ?></div>
            <a class="back-btn" href="/<?= htmlspecialchars($project) ?>/SkolaMainPage/Lapa.php">← Atpakaļ</a>
        </div>
    </div>
</div>

<footer class="end-container">
    <p>© 2025 Kalvenes Pamatskola. Visas tiesības aizsargātas.</p>
</footer>

<script src="/<?= htmlspecialchars($project) ?>/SkolaMainPage/script.js?v=20260403"></script>
</body>
</html>
