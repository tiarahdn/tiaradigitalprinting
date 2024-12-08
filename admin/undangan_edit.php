<?php
session_start();
require_once '../config/config.php';
require_once '../includes/functions.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = sanitize_input($_GET['id']);

    // Ambil data undangan berdasarkan ID
    $sql = "SELECT * FROM undangan WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $undangan = $result->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Proses update undangan
        $jenis = sanitize_input($_POST['jenis']);
        $keterangan = sanitize_input($_POST['keterangan']);
        $foto = $undangan['foto']; // Tetap gunakan foto yang lama jika tidak ada foto baru

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($_FILES["foto"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($imageFileType, $allowed_types)) {
                if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                    $foto = basename($_FILES["foto"]["name"]);
                }
            }
        }

        // Update data undangan
        $sql_update = "UPDATE undangan SET jenis = ?, keterangan = ?, foto = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sssi", $jenis, $keterangan, $foto, $id);
        $stmt_update->execute();
        header("Location: dashboard.php");
    }
} else {
    echo "ID Undangan tidak ditemukan.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Undangan</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>
    <h2>Edit Undangan</h2>
    <form method="post" action="" enctype="multipart/form-data">
        <label for="jenis">Jenis Undangan</label><br>
        <input type="text" id="jenis" name="jenis" value="<?php echo $undangan['jenis']; ?>" required><br><br>

        <label for="keterangan">Keterangan</label><br>
        <textarea id="keterangan" name="keterangan" required><?php echo $undangan['keterangan']; ?></textarea><br><br>

        <label for="foto">Foto Undangan</label><br>
        <input type="file" id="foto" name="foto" accept="image/*"><br><br>
        <small>Biarkan kosong jika tidak ingin mengganti foto.</small><br><br>

        <button type="submit">Update Undangan</button>
    </form>
</body>

</html>