<?php

use function PHPSTORM_META\type;

require 'header.php';

$query = "SELECT * FROM rooms ORDER BY id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Data Kamar</h1>
    <a href="tambah-kamar.php" class="btn btn-primary mb-3">Tambah Kamar</a>
    <a href="index.php" class="btn btn-secondary mb-3">Kembali</a>

    <!-- Tabel Data Kamar -->
    <table class=" table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nomor Kamar</th>
                <th>Tipe Kamar</th>
                <th>Harga</th>
                <th>Fasilitas</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($rooms) > 0): ?>
                <?php foreach ($rooms as $index => $room): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo $room['nomor_kamar']; ?></td>
                        <td><?php echo $room['tipe_kamar']; ?></td>
                        <td>Rp<?php echo number_format($room['harga'], 2, ',', '.'); ?></td>

                        <td>
                            <?php
                            if (!empty($room['fasilitas'])) {
                                echo implode(', ', explode(',', $room['fasilitas']));
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($room['stok'] > 0) {
                                echo '<span class="badge bg-success">' . $room['stok'] . ' Tersedia</span>';
                            } else {
                                echo '<span class="badge bg-danger">Habis</span>';
                            }
                            ?>
                        </td>
                        <td>
                            <a href="edit-kamar.php?id=<?php echo $room['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="hapus-kamar.php?id=<?php echo $room['id']; ?>" class="btn btn-danger btn-sm"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus kamar ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data kamar.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

<?php
require 'footer.php';
?>