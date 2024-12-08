<?php
session_start();
require_once '../config/config.php';
require_once '../includes/functions.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jenis = sanitize_input($_POST['jenis']);
    $keterangan = sanitize_input($_POST['keterangan']);

    // Proses upload gambar
    $foto = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["foto"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        // Cek apakah file gambar valid
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                $foto = basename($_FILES["foto"]["name"]);
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        } else {
            $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }

    // Insert undangan ke database
    $sql = "INSERT INTO undangan (jenis, keterangan, foto) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $jenis, $keterangan, $foto);
    $stmt->execute();
    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Undangan</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>
    <h2>Tambah Undangan</h2>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <form method="post" action="" enctype="multipart/form-data">
        <label for="jenis">Jenis Undangan</label><br>
        <input type="text" id="jenis" name="jenis" required><br><br>

        <label for="keterangan">Keterangan</label><br>
        <textarea id="keterangan" name="keterangan" required></textarea><br><br>

        <label for="foto">Foto Undangan</label><br>
        <input type="file" id="foto" name="foto" accept="image/*"><br><br>

        <button type="submit">Tambah Undangan</button>
    </form>
</body>

</html>