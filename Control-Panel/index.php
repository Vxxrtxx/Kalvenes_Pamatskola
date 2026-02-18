<?php
include "config.php";

if (isset($_POST['save_content'])) {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $id = 1;

    if (!empty($_FILES['content_image']['name'])) {
        $imageName = time() . "_" . $_FILES['content_image']['name'];
        move_uploaded_file($_FILES['content_image']['tmp_name'], "uploads/" . $imageName);

        $stmt = $conn->prepare("UPDATE content SET title=?, description=?, image=? WHERE id=?");
        $stmt->bind_param("sssi", $title, $description, $imageName, $id);
    } else {
        $stmt = $conn->prepare("UPDATE content SET title=?, description=? WHERE id=?");
        $stmt->bind_param("ssi", $title, $description, $id);
    }

    $stmt->execute();
}

if (isset($_POST['upload_media'])) {
    $imageName = time() . "_" . $_FILES['media_image']['name'];
    move_uploaded_file($_FILES['media_image']['tmp_name'], "uploads/" . $imageName);
}

if (isset($_GET['delete'])) {
    $file = $_GET['delete'];
    unlink("uploads/" . $file);
    header("Location: index.php");
    exit;
}

$result = $conn->query("SELECT * FROM content WHERE id=1");
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Control Panel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="dashboard">

    <aside class="sidebar">
        <h2>Control Panel</h2>
        <ul>
            <li class="active">Dashboard</li>
        </ul>
    </aside>

    <main class="main">

        <!-- EDIT CONTENT -->
        <div class="card">
            <h1>Edit Website Content</h1>

            <form method="POST" enctype="multipart/form-data">

                <label>Title</label>
                <input type="text" name="title"
                    value="<?php echo htmlspecialchars($row['title'] ?? ''); ?>" required>

                <label>Description</label>
                <textarea name="description" required><?php echo htmlspecialchars($row['description'] ?? ''); ?></textarea>

                <label>Current Image</label>
                <?php if (!empty($row['image'])): ?>
                    <div class="image-preview">
                        <img src="uploads/<?php echo $row['image']; ?>">
                    </div>
                <?php else: ?>
                    <p style="opacity:0.6;">No image uploaded</p>
                <?php endif; ?>

                <label>Upload New Image</label>
                <input type="file" name="content_image">

                <button type="submit" name="save_content">Save Changes</button>
            </form>
        </div>

        <div class="card" style="margin-top:40px;">
            <h1>Media Manager</h1>

            <form method="POST" enctype="multipart/form-data">
                <label>Upload Image</label>
                <input type="file" name="media_image" required>
                <button type="submit" name="upload_media">Upload</button>
            </form>
            
 <!-- MEDIA MANAGER -->
            <div class="media-grid">
                <?php
                $files = scandir("uploads/");
                foreach ($files as $file) {
                    if ($file != "." && $file != "..") {
                        echo "
                        <div class='media-item'>
                            <img src='uploads/$file'>
                            <a href='?delete=$file' class='delete'>Delete</a>
                        </div>
                        ";
                    }
                }
                ?>
            </div>
        </div>

    </main>
</div>

</body>
</html>
