<?php
session_start();
require_once '../config/config.php';
require_once '../includes/functions.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id_undangan'])) {
    die('ID Undangan tidak ditemukan!');
}

$id_undangan = $_GET['id_undangan'];

// Query untuk mendapatkan data harga berdasarkan id_undangan
$query = "SELECT * FROM harga WHERE id_undangan = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_undangan);
$stmt->execute();
header("Location: dashboard.php");
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die('Harga untuk undangan ini tidak ditemukan!');
}

$data = $result->fetch_assoc();

// Update harga jika formulir disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jenis_kertas = $_POST['jenis_kertas'];
    $harga_ecer = $_POST['harga_ecer'];
    $harga_grosir = $_POST['harga_grosir'];
    $minimal_pembelian = $_POST['minimal_pembelian'];

    // Query untuk mengupdate harga
    $update_query = "UPDATE harga SET jenis_kertas = ?, harga_ecer = ?, harga_grosir = ?, minimal_pembelian = ? WHERE id_undangan = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sdiii", $jenis_kertas, $harga_ecer, $harga_grosir, $minimal_pembelian, $id_undangan);

    if ($stmt->execute()) {
        echo "Harga berhasil diperbarui!";
    } else {
        echo "Terjadi kesalahan saat memperbarui harga.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Harga</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>
    <h2>Edit Harga Undangan</h2>

    <form method="post">
        <label for="jenis_kertas">Jenis Kertas:</label><br>
        <input type="text" id="jenis_kertas" name="jenis_kertas" value="<?php echo htmlspecialchars($data['jenis_kertas']); ?>" required><br><br>

        <label for="harga_ecer">Harga Ecer (per pcs):</label><br>
        <input type="number" id="harga_ecer" name="harga_ecer" value="<?php echo $data['harga_ecer']; ?>" required><br><br>

        <label for="harga_grosir">Harga Grosir (per pcs):</label><br>
        <input type="number" id="harga_grosir" name="harga_grosir" value="<?php echo $data['harga_grosir']; ?>" required><br><br>

        <label for="minimal_pembelian">Minimal Pembelian (pcs):</label><br>
        <input type="number" id="minimal_pembelian" name="minimal_pembelian" value="<?php echo $data['minimal_pembelian']; ?>" required><br><br>

        <button type="submit">Update Harga</button>
    </form>
</body>

</html>