<?php
include "config.php";

/* HERO UPDATE */
if(isset($_POST['update_hero'])){
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];

    $conn->query("UPDATE content SET title='$title', subtitle='$subtitle' WHERE id=1");

    if(!empty($_FILES['video']['name'])){
        $video = "/SkolaMainPage/SkolasAtteli/" . $_FILES['video']['name'];
        move_uploaded_file($_FILES['video']['tmp_name'], "../SkolaMainPage/SkolasAtteli/" . $_FILES['video']['name']);
        $conn->query("UPDATE content SET video_path='$video' WHERE id=1");
    }
}

/* ADD AKTUALITATE */
if(isset($_POST['add_akt'])){
    $text = $_POST['text'];
    $img = "/admin/uploads/" . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $_FILES['image']['name']);
    $conn->query("INSERT INTO aktualitates (image,text) VALUES ('$img','$text')");
}

/* DELETE AKT */
if(isset($_GET['delete_akt'])){
    $conn->query("DELETE FROM aktualitates WHERE id=".$_GET['delete_akt']);
}

/* ADD TIMELINE */
if(isset($_POST['add_time'])){
    $title = $_POST['time_title'];
    $desc = $_POST['time_desc'];
    $img = "/admin/uploads/" . $_FILES['time_img']['name'];
    move_uploaded_file($_FILES['time_img']['tmp_name'], "uploads/" . $_FILES['time_img']['name']);
    $conn->query("INSERT INTO timeline (image,title,description) VALUES ('$img','$title','$desc')");
}

/* DELETE TIMELINE */
if(isset($_GET['delete_time'])){
    $conn->query("DELETE FROM timeline WHERE id=".$_GET['delete_time']);
}

$hero = $conn->query("SELECT * FROM content WHERE id=1")->fetch_assoc();
$akt = $conn->query("SELECT * FROM aktualitates");
$time = $conn->query("SELECT * FROM timeline");
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
<title>Admin Panel</title>
</head>
<body>

<h1>Admin Panel</h1>

<h2>Hero Section</h2>
<form method="POST" enctype="multipart/form-data">
<input type="text" name="title" value="<?= $hero['title'] ?>">
<input type="text" name="subtitle" value="<?= $hero['subtitle'] ?>">
<input type="file" name="video">
<button name="update_hero">Update Hero</button>
</form>

<hr>

<h2>Aktualitātes</h2>
<form method="POST" enctype="multipart/form-data">
<input type="file" name="image" required>
<textarea name="text" placeholder="Text"></textarea>
<button name="add_akt">Add</button>
</form>

<?php while($row=$akt->fetch_assoc()): ?>
<div>
<img src="<?= $row['image'] ?>" width="100">
<p><?= $row['text'] ?></p>
<a href="?delete_akt=<?= $row['id'] ?>">Delete</a>
</div>
<?php endwhile; ?>

<hr>

<h2>Timeline</h2>
<form method="POST" enctype="multipart/form-data">
<input type="file" name="time_img" required>
<input type="text" name="time_title">
<textarea name="time_desc"></textarea>
<button name="add_time">Add</button>
</form>

<?php while($row=$time->fetch_assoc()): ?>
<div>
<img src="<?= $row['image'] ?>" width="100">
<h4><?= $row['title'] ?></h4>
<p><?= $row['description'] ?></p>
<a href="?delete_time=<?= $row['id'] ?>">Delete</a>
</div>
<?php endwhile; ?>

</body>
</html>