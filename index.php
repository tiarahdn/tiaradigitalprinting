<?php
require_once 'config/config.php';

// Fetch data undangan
$sql = "SELECT * FROM undangan";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="css/styles.css">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiara Digital Printing</title>
</head>

<body>
    <h1>Tiara Digital Printing</h1>
    <p>Daftar Undangan yang Tersedia:</p>
    <div class="undangan-list">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="undangan-item">
                <h3><?php echo $row['jenis']; ?></h3>
                <p><?php echo $row['keterangan']; ?></p>
                <?php if ($row['foto']): ?>
                    <img src="uploads/<?php echo $row['foto']; ?>" alt="Foto Undangan" width="300"><br>
                <?php endif; ?>
                <a href="detail.php?id=<?php echo $row['id']; ?>">Lihat Detail</a>
            </div>
        <?php endwhile; ?>
    </div>
</body>

</html>