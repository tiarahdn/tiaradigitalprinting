<?php
require_once '../config/config.php'; // Pastikan jalur ini sudah benar
require_once '../includes/functions.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Validasi input

    // Ambil nama file foto sebelum dihapus
    $sql = "SELECT foto FROM undangan WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $undangan = $result->fetch_assoc();

    if ($undangan) {
        // Hapus file foto dari folder jika ada
        if (!empty($undangan['foto']) && file_exists('../uploads/' . $undangan['foto'])) {
            unlink('../uploads/' . $undangan['foto']);
        }

        // Hapus data terkait di tabel harga
        $sql_delete_harga = "DELETE FROM harga WHERE id_undangan = ?";
        $stmt_harga = $conn->prepare($sql_delete_harga);
        $stmt_harga->bind_param("i", $id);
        $stmt_harga->execute();

        // Hapus data undangan dari database
        $sql_delete_undangan = "DELETE FROM undangan WHERE id = ?";
        $stmt_undangan = $conn->prepare($sql_delete_undangan);
        $stmt_undangan->bind_param("i", $id);
        $stmt_undangan->execute();

        if ($stmt_undangan->affected_rows > 0) {
            echo "Data berhasil dihapus.";
        } else {
            echo "Gagal menghapus data.";
        }
    } else {
        echo "Data tidak ditemukan.";
    }
} else {
    echo "ID tidak valid.";
}

header("Location: dashboard.php");
exit();
