<?php
require_once 'config/config.php';
require_once 'includes/functions.php';

if (isset($_GET['id'])) {
    $id = sanitize_input($_GET['id']);

    // Ambil data undangan berdasarkan ID
    $sql = "SELECT * FROM undangan WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $undangan = $result->fetch_assoc();

    if ($undangan) {
        // Ambil harga berdasarkan ID undangan
        $sql_harga = "SELECT * FROM harga WHERE id_undangan = ?";
        $stmt_harga = $conn->prepare($sql_harga);
        $stmt_harga->bind_param("i", $id);
        $stmt_harga->execute();
        $result_harga = $stmt_harga->get_result();
    } else {
        echo "Undangan tidak ditemukan.";
        exit();
    }
} else {
    echo "ID Undangan tidak valid.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Undangan</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div class="container">
        <h1>Detail Undangan</h1>
        <div class="undangan-detail">
            <h2><?php echo $undangan['jenis']; ?></h2>
            <p><?php echo nl2br($undangan['keterangan']); ?></p>

            <?php if ($undangan['foto']): ?>
                <img src="uploads/<?php echo $undangan['foto']; ?>" alt="Foto Undangan" class="detail-img"><br><br>
            <?php endif; ?>

            <h3>Harga Berdasarkan Jenis Kertas</h3>
            <table class="price-table">
                <tr>
                    <th>Jenis Kertas</th>
                    <th>Harga Ecer</th>
                    <th>Harga Grosir</th>
                    <th>Minimal Pembelian</th>
                </tr>
                <?php while ($row_harga = $result_harga->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row_harga['jenis_kertas']; ?></td>
                        <td>Rp. <?php echo number_format($row_harga['harga_ecer'], 0, ',', '.'); ?>/pcs</td>
                        <td>Rp. <?php echo number_format($row_harga['harga_grosir'], 0, ',', '.'); ?>/pcs</td>
                        <td><?php echo $row_harga['minimal_pembelian']; ?> pcs</td>
                    </tr>
                <?php endwhile; ?>
            </table>

            <div class="action-buttons">
                <a href="index.php" class="btn">Kembali ke Katalog</a>
                <a href="https://wa.me/6283104028554?text=Halo%2C%20saya%20tertarik%20dengan%20undangan%20jenis%20<?php echo urlencode($undangan['jenis']); ?>.%20Apakah%20saya%20bisa%20mendapatkan%20informasi%20lebih%20lanjut%3F" target="_blank" class="whatsapp-button">Hubungi via WhatsApp</a>
            </div>
        </div>
    </div>
</body>

</html>