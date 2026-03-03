<?php
// ====== DB CONNECT ======
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli("localhost", "root", "", "school_site");
$conn->set_charset("utf8mb4");

// Auto-detect project folder name (first segment after localhost/)
$PROJECT = explode("/", trim($_SERVER["SCRIPT_NAME"], "/"))[0]; // e.g. Kalvenes_Pamatskola

// Panel base URL (this file lives in /Kalvenes_Pamatskola/Control-Panel/index.php)
$PANEL_BASE_URL = "/" . $PROJECT . "/Control-Panel";
$UPLOAD_WEB_DIR = $PANEL_BASE_URL . "/uploads";          // URL used by browser
$UPLOAD_FS_DIR  = __DIR__ . DIRECTORY_SEPARATOR . "uploads"; // filesystem path

// Website assets dir for hero videos (in your main page folder)
$HERO_VIDEO_WEB_DIR = "/" . $PROJECT . "/SkolaMainPage/SkolasAtteli";
$HERO_VIDEO_FS_DIR  = __DIR__ . "/../SkolaMainPage/SkolasAtteli";

$msg = "";

// ====== HELPERS ======
function safe_filename(string $name): string {
    $base = preg_replace('/[^a-zA-Z0-9_\-]/', '_', pathinfo($name, PATHINFO_FILENAME));
    $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    return [$base, $ext];
}

function upload_file(string $inputName, string $destFsDir, string $destWebDir, array $allowedExt): array {
    // returns [ok(bool), webPath(?string), error(?string)]
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
            $GLOBALS["HERO_VIDEO_FS_DIR"],
            $GLOBALS["HERO_VIDEO_WEB_DIR"],
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
    $text = trim($_POST["text"] ?? "");
    if ($text === "") {
        header("Location: index.php?msg=" . urlencode("Aktualitāte text is empty."));
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

    $stmt = $conn->prepare("INSERT INTO aktualitates (image, text) VALUES (?, ?)");
    $stmt->bind_param("ss", $imgWeb, $text);
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
    $title = trim($_POST["time_title"] ?? "");
    $desc  = trim($_POST["time_desc"] ?? "");

    if ($title === "" || $desc === "") {
        header("Location: index.php?msg=" . urlencode("Timeline title/description is empty."));
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

    $stmt = $conn->prepare("INSERT INTO timeline (image, title, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $imgWeb, $title, $desc);
    $stmt->execute();

    header("Location: index.php?msg=" . urlencode("Timeline added."));
    exit;
}

// ====== DELETE TIMELINE ======
if (isset($_GET["delete_time"])) {
    $id = (int)$_GET["delete_time"];
    $stmt = $conn->prepare("DELETE FROM timeline WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: index.php?msg=" . urlencode("Timeline deleted."));
    exit;
}

// ====== FETCH DATA ======
$hero = $conn->query("SELECT * FROM content WHERE id=1")->fetch_assoc();
$akt  = $conn->query("SELECT * FROM aktualitates ORDER BY id DESC");
$time = $conn->query("SELECT * FROM timeline ORDER BY id DESC");

$msg = $_GET["msg"] ?? "";
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="style.css">
    <title>Control Panel</title>
</head>
<body>

<h1>Control Panel</h1>

<?php if ($msg): ?>
    <p style="padding:10px 12px;border-radius:10px;background:rgba(0,0,0,0.08);display:inline-block;">
        <?= htmlspecialchars($msg) ?>
    </p>
<?php endif; ?>

<h2>Hero Section</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="title" value="<?= htmlspecialchars($hero["title"] ?? "") ?>" placeholder="Title" required>
    <input type="text" name="subtitle" value="<?= htmlspecialchars($hero["subtitle"] ?? "") ?>" placeholder="Subtitle" required>
    <input type="file" name="video" accept="video/mp4,video/webm,video/ogg">
    <button type="submit" name="update_hero">Update Hero</button>
</form>

<p style="opacity:.75;margin-top:8px;">
    Current video: <code><?= htmlspecialchars($hero["video_path"] ?? "") ?></code>
</p>

<hr>

<h2>Aktualitātes</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="image" accept="image/png,image/jpeg,image/webp" required>
    <textarea name="text" placeholder="Text" required></textarea>
    <button type="submit" name="add_akt">Add</button>
</form>

<?php while($row = $akt->fetch_assoc()): ?>
    <div style="margin:14px 0;">
        <img src="<?= htmlspecialchars($row["image"]) ?>" width="120" style="border-radius:10px;">
        <p><?= htmlspecialchars($row["text"]) ?></p>
        <div style="opacity:.7;font-size:.9rem;">
            <code><?= htmlspecialchars($row["image"]) ?></code>
        </div>
        <a href="?delete_akt=<?= (int)$row["id"] ?>">Delete</a>
    </div>
<?php endwhile; ?>

<hr>

<h2>Timeline</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="time_img" accept="image/png,image/jpeg,image/webp" required>
    <input type="text" name="time_title" placeholder="Title" required>
    <textarea name="time_desc" placeholder="Description" required></textarea>
    <button type="submit" name="add_time">Add</button>
</form>

<?php while($row = $time->fetch_assoc()): ?>
    <div style="margin:14px 0;">
        <img src="<?= htmlspecialchars($row["image"]) ?>" width="120" style="border-radius:10px;">
        <h4><?= htmlspecialchars($row["title"]) ?></h4>
        <p><?= htmlspecialchars($row["description"]) ?></p>
        <div style="opacity:.7;font-size:.9rem;">
            <code><?= htmlspecialchars($row["image"]) ?></code>
        </div>
        <a href="?delete_time=<?= (int)$row["id"] ?>">Delete</a>
    </div>
<?php endwhile; ?>

</body>
</html>