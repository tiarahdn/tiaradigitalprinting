<?php
session_start();
require_once '../config/config.php';
require_once '../includes/functions.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

// Fetch undangan data
$result = $conn->query("SELECT * FROM undangan");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>
    <h2>Dashboard Admin</h2>
    <p>Welcome, Admin</p>
    <a href="undangan_tambah.php">Tambah Undangan</a><br><br>
    <h3>Daftar Undangan</h3>
    <table border="1">
        <tr>
            <th>Jenis Undangan</th>
            <th>Keterangan</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['jenis']; ?></td>
                <td><?php echo $row['keterangan']; ?></td>
                <td>
                    <a href="undangan_edit.php?id=<?php echo $row['id']; ?>">Edit</a> |
                    <a href="harga_tambah.php?id_undangan=<?php echo $row['id']; ?>">Tambah Harga</a> |
                    <a href="harga_edit.php?id_undangan=<?php echo $row['id']; ?>">Edit Harga</a>
                    <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus undangan ini?')">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>