<?php
session_start();
require_once '../config/config.php';
require_once '../includes/functions.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

$id_undangan = sanitize_input($_GET['id_undangan']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jenis_kertas = sanitize_input($_POST['jenis_kertas']);
    $harga_ecer = sanitize_input($_POST['harga_ecer']);
    $harga_grosir = sanitize_input($_POST['harga_grosir']);
    $minimal_pembelian = sanitize_input($_POST['minimal_pembelian']);

    $sql = "INSERT INTO harga (id_undangan, jenis_kertas, harga_ecer, harga_grosir, minimal_pembelian) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issdi", $id_undangan, $jenis_kertas, $harga_ecer, $harga_grosir, $minimal_pembelian);
    $stmt->execute();
    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Harga</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>
    <h2>Tambah Harga</h2>
    <form method="post" action="">
        <label for="jenis_kertas">Jenis Kertas</label><br>
        <input type="text" id="jenis_kertas" name="jenis_kertas" required><br><br>
        <label for="harga_ecer">Harga Ecer</label><br>
        <input type="number" id="harga_ecer" name="harga_ecer" required><br><br>
        <label for="harga_grosir">Harga Grosir</label><br>
        <input type="number" id="harga_grosir" name="harga_grosir" required><br><br>
        <label for="minimal_pembelian">Minimal Pembelian</label><br>
        <input type="number" id="minimal_pembelian" name="minimal_pembelian" required><br><br>
        <button type="submit">Tambah Harga</button>
    </form>
</body>

</html>