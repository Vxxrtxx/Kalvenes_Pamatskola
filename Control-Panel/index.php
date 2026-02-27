<?php
include "config.php";

/* ---------- helpers ---------- */
function uploadImage(string $inputName, string $destDir, array $allowedExt = ["jpg","jpeg","png","webp"]): ?string {
    if (empty($_FILES[$inputName]["name"])) return null;

    $name = $_FILES[$inputName]["name"];
    $tmp  = $_FILES[$inputName]["tmp_name"];
    $err  = $_FILES[$inputName]["error"];

    if ($err !== UPLOAD_ERR_OK) return null;

    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt, true)) return null;

    if (!is_dir($destDir)) mkdir($destDir, 0777, true);

    $safeBase = preg_replace('/[^a-zA-Z0-9_\-]/', '_', pathinfo($name, PATHINFO_FILENAME));
    $finalName = time() . "_" . $safeBase . "." . $ext;
    $finalPath = rtrim($destDir, "/\\") . DIRECTORY_SEPARATOR . $finalName;

    if (!move_uploaded_file($tmp, $finalPath)) return null;

    // return web path (IMPORTANT)
    return "/KalvenesPamataskola/admin/uploads/" . $finalName;
}

/* ---------- HERO UPDATE ---------- */
if (isset($_POST["update_hero"])) {
    $title = $_POST["title"] ?? "";
    $subtitle = $_POST["subtitle"] ?? "";

    // update text
    $stmt = $conn->prepare("UPDATE content SET title=?, subtitle=? WHERE id=1");
    $stmt->bind_param("ss", $title, $subtitle);
    $stmt->execute();

    // optional video upload
    if (!empty($_FILES["video"]["name"])) {
        $videoName = $_FILES["video"]["name"];
        $videoTmp  = $_FILES["video"]["tmp_name"];

        $videoExt = strtolower(pathinfo($videoName, PATHINFO_EXTENSION));
        $allowedVideo = ["mp4","webm","ogg"];
        if (in_array($videoExt, $allowedVideo, true)) {
            $dest = __DIR__ . "/../SkolaMainPage/SkolasAtteli";
            if (!is_dir($dest)) mkdir($dest, 0777, true);

            $safeBase = preg_replace('/[^a-zA-Z0-9_\-]/', '_', pathinfo($videoName, PATHINFO_FILENAME));
            $finalName = time() . "_" . $safeBase . "." . $videoExt;

            move_uploaded_file($videoTmp, $dest . "/" . $finalName);

            $videoPath = "/KalvenesPamataskola/SkolaMainPage/SkolasAtteli/" . $finalName;
            $stmt2 = $conn->prepare("UPDATE content SET video_path=? WHERE id=1");
            $stmt2->bind_param("s", $videoPath);
            $stmt2->execute();
        }
    }

    header("Location: index.php");
    exit;
}

/* ---------- ADD AKTUALITATE ---------- */
if (isset($_POST["add_akt"])) {
    $text = $_POST["text"] ?? "";

    $imgPath = uploadImage("image", __DIR__ . "/uploads");
    if ($imgPath) {
        $stmt = $conn->prepare("INSERT INTO aktualitates (image, text) VALUES (?, ?)");
        $stmt->bind_param("ss", $imgPath, $text);
        $stmt->execute();
    }

    header("Location: index.php");
    exit;
}

/* ---------- DELETE AKTUALITATE ---------- */
if (isset($_GET["delete_akt"])) {
    $id = (int)$_GET["delete_akt"];
    $stmt = $conn->prepare("DELETE FROM aktualitates WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: index.php");
    exit;
}

/* ---------- ADD TIMELINE ---------- */
if (isset($_POST["add_time"])) {
    $title = $_POST["time_title"] ?? "";
    $desc  = $_POST["time_desc"] ?? "";

    $imgPath = uploadImage("time_img", __DIR__ . "/uploads");
    if ($imgPath) {
        $stmt = $conn->prepare("INSERT INTO timeline (image, title, description) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $imgPath, $title, $desc);
        $stmt->execute();
    }

    header("Location: index.php");
    exit;
}

/* ---------- DELETE TIMELINE ---------- */
if (isset($_GET["delete_time"])) {
    $id = (int)$_GET["delete_time"];
    $stmt = $conn->prepare("DELETE FROM timeline WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: index.php");
    exit;
}

/* ---------- FETCH DATA ---------- */
$hero = $conn->query("SELECT * FROM content WHERE id=1")->fetch_assoc();
$akt  = $conn->query("SELECT * FROM aktualitates ORDER BY id DESC");
$time = $conn->query("SELECT * FROM timeline ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="style.css">
    <title>Admin Panel</title>
</head>
<body>

<h1>Admin Panel</h1>

<h2>Hero Section</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="title" value="<?= htmlspecialchars($hero["title"] ?? "") ?>" placeholder="Title" required>
    <input type="text" name="subtitle" value="<?= htmlspecialchars($hero["subtitle"] ?? "") ?>" placeholder="Subtitle" required>
    <input type="file" name="video" accept="video/mp4,video/webm,video/ogg">
    <button type="submit" name="update_hero">Update Hero</button>
</form>

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
        <a href="?delete_time=<?= (int)$row["id"] ?>">Delete</a>
    </div>
<?php endwhile; ?>

</body>
</html>