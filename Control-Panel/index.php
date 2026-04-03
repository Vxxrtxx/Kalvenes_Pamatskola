<?php
// ====== DB CONNECT ======
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli("localhost", "root", "", "school_site");
$conn->set_charset("utf8mb4");

// Auto-detect project folder name
$PROJECT = explode("/", trim($_SERVER["SCRIPT_NAME"], "/"))[0];

$PANEL_BASE_URL = "/" . $PROJECT . "/Control-Panel";
$UPLOAD_WEB_DIR = $PANEL_BASE_URL . "/uploads";
$UPLOAD_FS_DIR  = __DIR__ . DIRECTORY_SEPARATOR . "uploads";

$HERO_VIDEO_WEB_DIR = "/" . $PROJECT . "/SkolaMainPage/SkolasAtteli";
$HERO_VIDEO_FS_DIR  = __DIR__ . "/../SkolaMainPage/SkolasAtteli";

$msg = "";

// ====== HELPERS ======
function upload_file(string $inputName, string $destFsDir, string $destWebDir, array $allowedExt): array {
    if (empty($_FILES[$inputName]["name"])) {
        return [false, null, "No file selected"];
    }

    $name = $_FILES[$inputName]["name"];
    $tmp  = $_FILES[$inputName]["tmp_name"];
    $err  = $_FILES[$inputName]["error"];

    if ($err !== UPLOAD_ERR_OK) {
        return [false, null, "Upload error code: $err"];
    }

    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt, true)) {
        return [false, null, "Invalid file type. Allowed: " . implode(", ", $allowedExt)];
    }

    if (!is_dir($destFsDir)) {
        if (!mkdir($destFsDir, 0777, true)) {
            return [false, null, "Failed to create upload folder"];
        }
    }

    $base = preg_replace('/[^a-zA-Z0-9_\-]/', '_', pathinfo($name, PATHINFO_FILENAME));
    $finalName = time() . "_" . $base . "." . $ext;
    $finalFs   = rtrim($destFsDir, "/\\") . DIRECTORY_SEPARATOR . $finalName;

    if (!move_uploaded_file($tmp, $finalFs)) {
        return [false, null, "Failed to move uploaded file"];
    }

    $webPath = rtrim($destWebDir, "/") . "/" . $finalName;
    return [true, $webPath, null];
}

// ====== HERO UPDATE ======
if (isset($_POST["update_hero"])) {
    $title = trim($_POST["title"] ?? "");
    $subtitle = trim($_POST["subtitle"] ?? "");

    $stmt = $conn->prepare("UPDATE content SET title=?, subtitle=? WHERE id=1");
    $stmt->bind_param("ss", $title, $subtitle);
    $stmt->execute();

    if (!empty($_FILES["video"]["name"])) {
        [$ok, $videoWeb, $err] = upload_file(
            "video",
            $HERO_VIDEO_FS_DIR,
            $HERO_VIDEO_WEB_DIR,
            ["mp4","webm","ogg"]
        );

        if ($ok && $videoWeb) {
            $stmt2 = $conn->prepare("UPDATE content SET video_path=? WHERE id=1");
            $stmt2->bind_param("s", $videoWeb);
            $stmt2->execute();
            $msg = "Hero updated (video uploaded).";
        } else {
            $msg = "Hero updated, but video failed: " . ($err ?? "Unknown error");
        }
    } else {
        $msg = "Hero updated.";
    }

    header("Location: index.php?msg=" . urlencode($msg));
    exit;
}

// ====== ADD AKTUALITATE ======
if (isset($_POST["add_akt"])) {
    $title   = trim($_POST["title"] ?? "");
    $text    = trim($_POST["text"] ?? "");
    $details = trim($_POST["details"] ?? "");

    if ($title === "" || $text === "" || $details === "") {
        header("Location: index.php?msg=" . urlencode("Aktualitāte fields cannot be empty."));
        exit;
    }

    [$ok, $imgWeb, $err] = upload_file(
        "image",
        $UPLOAD_FS_DIR,
        $UPLOAD_WEB_DIR,
        ["jpg","jpeg","png","webp"]
    );

    if (!$ok || !$imgWeb) {
        header("Location: index.php?msg=" . urlencode("Image upload failed: " . ($err ?? "Unknown error")));
        exit;
    }

    $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $title), '-'));
    if ($slug === '') {
        $slug = 'aktualitate-' . time();
    }

    $stmt = $conn->prepare("INSERT INTO aktualitates (image, slug, title, text, details) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $imgWeb, $slug, $title, $text, $details);
    $stmt->execute();

    header("Location: index.php?msg=" . urlencode("Aktualitāte added."));
    exit;
}

// ====== DELETE AKTUALITATE ======
if (isset($_GET["delete_akt"])) {
    $id = (int)$_GET["delete_akt"];
    $stmt = $conn->prepare("DELETE FROM aktualitates WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: index.php?msg=" . urlencode("Aktualitāte deleted."));
    exit;
}

// ====== ADD TIMELINE ======
if (isset($_POST["add_time"])) {
    $title   = trim($_POST["time_title"] ?? "");
    $desc    = trim($_POST["time_desc"] ?? "");
    $details = trim($_POST["time_details"] ?? "");

    if ($title === "" || $desc === "" || $details === "") {
        header("Location: index.php?msg=" . urlencode("Timeline fields cannot be empty."));
        exit;
    }

    [$ok, $imgWeb, $err] = upload_file(
        "time_img",
        $UPLOAD_FS_DIR,
        $UPLOAD_WEB_DIR,
        ["jpg","jpeg","png","webp"]
    );

    if (!$ok || !$imgWeb) {
        header("Location: index.php?msg=" . urlencode("Image upload failed: " . ($err ?? "Unknown error")));
        exit;
    }

    $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $title), '-'));
    if ($slug === '') {
        $slug = 'timeline-' . time();
    }

    $stmt = $conn->prepare("INSERT INTO timeline (image, slug, title, description, details) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $imgWeb, $slug, $title, $desc, $details);
    $stmt->execute();

    header("Location: index.php?msg=" . urlencode("Timeline added."));
    exit;
}

// ====== UPDATE CONTACTS ======
if (isset($_POST["update_contacts"])) {
    $phone = trim($_POST["phone"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $address = trim($_POST["address"] ?? "");
    $map_link = trim($_POST["map_link"] ?? "");

    $stmt = $conn->prepare("UPDATE contacts SET phone=?, email=?, address=?, map_link=? WHERE id=1");
    $stmt->bind_param("ssss", $phone, $email, $address, $map_link);
    $stmt->execute();

    header("Location: index.php?msg=" . urlencode("Contacts updated."));
    exit;
}

// ====== FETCH DATA ======
$hero = $conn->query("SELECT * FROM content WHERE id=1")->fetch_assoc();
$akt  = $conn->query("SELECT * FROM aktualitates ORDER BY id DESC");
$time = $conn->query("SELECT * FROM timeline ORDER BY id DESC");
$contacts = $conn->query("SELECT * FROM contacts WHERE id=1")->fetch_assoc();

$aktCount = $akt ? $akt->num_rows : 0;
$timeCount = $time ? $time->num_rows : 0;

$msg = $_GET["msg"] ?? "";
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="Style.css?v=<?= urlencode((string)filemtime(__DIR__ . '/Style.css')) ?>">
    <title>Control Panel</title>
</head>
<body>

<div class="cp-top">
    <h1>Control Panel</h1>
    <p class="cp-subtitle">Pārvaldi mājaslapas saturu vienuviet: galveno sadaļu, aktualitātes, priekšrocības un kontaktinformāciju.</p>

    <div class="cp-stats">
        <div class="stat-card">
            <span class="stat-label">Aktualitātes</span>
            <strong><?= (int)$aktCount ?></strong>
        </div>
        <div class="stat-card">
            <span class="stat-label">Timeline ieraksti</span>
            <strong><?= (int)$timeCount ?></strong>
        </div>
        <div class="stat-card">
            <span class="stat-label">Hero virsraksts</span>
            <strong><?= !empty($hero['title']) ? 'Iestatīts' : 'Nav iestatīts' ?></strong>
        </div>
        <div class="stat-card">
            <span class="stat-label">Kontakti</span>
            <strong><?= !empty($contacts['phone']) ? 'Atjaunināti' : 'Nav datu' ?></strong>
        </div>
    </div>

    <nav class="cp-nav">
        <a href="#hero-section">Hero</a>
        <a href="#akt-section">Aktualitātes</a>
        <a href="#timeline-section">Timeline</a>
        <a href="#contacts-section">Kontakti</a>
    </nav>
</div>

<?php if ($msg): ?>
    <div class="flash-message">
        <?= htmlspecialchars($msg) ?>
    </div>
<?php endif; ?>

<div class="panel-shell">

    <section class="panel-card" id="hero-section">
        <div class="section-header">
            <div>
                <h2>Hero Section</h2>
                <p class="subtext">Main homepage title, subtitle, and hero video.</p>
            </div>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-grid">
            <div class="form-group">
                <label for="hero-title">Title</label>
                <input id="hero-title" type="text" name="title" value="<?= htmlspecialchars($hero["title"] ?? "") ?>" placeholder="Title" required>
            </div>

            <div class="form-group">
                <label for="hero-subtitle">Subtitle</label>
                <input id="hero-subtitle" type="text" name="subtitle" value="<?= htmlspecialchars($hero["subtitle"] ?? "") ?>" placeholder="Subtitle" required>
            </div>

            <div class="form-group">
                <label for="hero-video">Video</label>
                <input id="hero-video" type="file" name="video" accept="video/mp4,video/webm,video/ogg">
            </div>
            </div>

            <button class="btn-primary" type="submit" name="update_hero">Update Hero</button>
        </form>

        <p class="hero-meta">
            Current video: <code><?= htmlspecialchars($hero["video_path"] ?? "") ?></code>
        </p>
    </section>

    <section class="panel-card" id="akt-section">
        <div class="section-header">
            <div>
                <h2>Aktualitātes</h2>
                <p class="subtext">Add homepage news cards with image, title, description, and full details page text.</p>
            </div>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-grid">
            <div class="form-group">
                <label for="akt-image">Image</label>
                <input id="akt-image" type="file" name="image" accept="image/png,image/jpeg,image/webp" required>
            </div>

            <div class="form-group">
                <label for="akt-title">Title</label>
                <input id="akt-title" type="text" name="title" placeholder="Header / Title" required>
            </div>

            <div class="form-group">
                <label for="akt-text">Short Description</label>
                <textarea id="akt-text" name="text" placeholder="Short text shown on card" required></textarea>
            </div>

            <div class="form-group">
                <label for="akt-details">Full Details</label>
                <textarea id="akt-details" name="details" placeholder="Full text for the More Info page" required></textarea>
            </div>
            </div>

            <button class="btn-primary" type="submit" name="add_akt">Add</button>
        </form>

        <div class="items-grid">
            <?php while($row = $akt->fetch_assoc()): ?>
                <div class="item-card">
                    <img src="<?= htmlspecialchars($row["image"]) ?>" alt="Aktualitāte">
                    <div class="item-content">
                        <h4><?= htmlspecialchars($row["title"] ?? "") ?></h4>
                        <p><?= htmlspecialchars($row["text"]) ?></p>
                        <p><strong>Slug:</strong> <?= htmlspecialchars($row["slug"] ?? "") ?></p>
                        <p><strong>Full details:</strong> <?= htmlspecialchars($row["details"] ?? "") ?></p>
                        <div class="item-meta">
                            <code><?= htmlspecialchars($row["image"]) ?></code>
                        </div>
                    </div>
                    <a class="delete-link" href="?delete_akt=<?= (int)$row["id"] ?>">Delete</a>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <section class="panel-card" id="timeline-section">
        <div class="section-header">
            <div>
                <h2>Timeline</h2>
                <p class="subtext">Add homepage benefit cards with image, title, description, and full details page text.</p>
            </div>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-grid">
            <div class="form-group">
                <label for="time-img">Image</label>
                <input id="time-img" type="file" name="time_img" accept="image/png,image/jpeg,image/webp" required>
            </div>

            <div class="form-group">
                <label for="time-title">Title</label>
                <input id="time-title" type="text" name="time_title" placeholder="Title" required>
            </div>

            <div class="form-group">
                <label for="time-desc">Short Description</label>
                <textarea id="time-desc" name="time_desc" placeholder="Short text shown on card" required></textarea>
            </div>

            <div class="form-group">
                <label for="time-details">Full Details</label>
                <textarea id="time-details" name="time_details" placeholder="Full text for the More Info page" required></textarea>
            </div>
            </div>

            <button class="btn-primary" type="submit" name="add_time">Add</button>
        </form>

        <div class="items-grid">
            <?php while($row = $time->fetch_assoc()): ?>
                <div class="item-card">
                    <img src="<?= htmlspecialchars($row["image"]) ?>" alt="Timeline">
                    <div class="item-content">
                        <h4><?= htmlspecialchars($row["title"]) ?></h4>
                        <p><?= htmlspecialchars($row["description"]) ?></p>
                        <p><strong>Slug:</strong> <?= htmlspecialchars($row["slug"] ?? "") ?></p>
                        <p><strong>Full details:</strong> <?= htmlspecialchars($row["details"] ?? "") ?></p>
                        <div class="item-meta">
                            <code><?= htmlspecialchars($row["image"]) ?></code>
                        </div>
                    </div>
                    <a class="delete-link" href="?delete_time=<?= (int)$row["id"] ?>">Delete</a>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <section class="panel-card" id="contacts-section">
        <div class="section-header">
            <div>
                <h2>Contacts</h2>
                <p class="subtext">Update contact information displayed on the contacts page.</p>
            </div>
        </div>

        <form method="POST">
            <div class="form-grid">
            <div class="form-group">
                <label for="contacts-phone">Phone</label>
                <input id="contacts-phone" type="text" name="phone" value="<?= htmlspecialchars($contacts["phone"] ?? "") ?>" placeholder="Phone number" required>
            </div>

            <div class="form-group">
                <label for="contacts-email">Email</label>
                <input id="contacts-email" type="email" name="email" value="<?= htmlspecialchars($contacts["email"] ?? "") ?>" placeholder="Email address">
            </div>

            <div class="form-group">
                <label for="contacts-address">Address</label>
                <textarea id="contacts-address" name="address" placeholder="Full address" required><?= htmlspecialchars($contacts["address"] ?? "") ?></textarea>
            </div>

            <div class="form-group">
                <label for="contacts-map">Map Link</label>
                <input id="contacts-map" type="url" name="map_link" value="<?= htmlspecialchars($contacts["map_link"] ?? "") ?>" placeholder="Google Maps link">
            </div>
            </div>

            <button class="btn-primary" type="submit" name="update_contacts">Update Contacts</button>
        </form>
    </section>

</div>

</body>
</html>