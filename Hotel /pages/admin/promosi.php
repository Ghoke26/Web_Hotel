<?php
require 'header.php';


$query = "SELECT promotions.*, rooms.nomor_kamar 
          FROM promotions
          LEFT JOIN rooms ON promotions.kamar_id = rooms.id
          ORDER BY promotions.id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$promotions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Kelola Promosi</h1>
    <a href="tambah-promosi.php" class="btn btn-primary mb-3">Tambah Promosi</a>
    <a href="index.php" class="btn btn-secondary mb-3">Kembali</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Promosi</th>
                <th>Tipe Diskon</th>
                <th>Jumlah Diskon</th>
                <th>Kamar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($promotions) > 0): ?>
                <?php foreach ($promotions as $index => $promo): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo $promo['nama_promosi']; ?></td>
                        <td><?php echo ucfirst($promo['tipe_diskon']); ?></td>
                        <td>
                            <?php
                            echo $promo['tipe_diskon'] === 'persentase'
                                ? $promo['jumlah_diskon'] . '%'
                                : 'Rp' . number_format($promo['jumlah_diskon'], 2, ',', '.');
                            ?>
                        </td>
                        <td><?php echo $promo['nomor_kamar'] ?? 'Semua Kamar'; ?></td>
                        <td>
                            <a href="edit-promosi.php?id=<?php echo $promo['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="hapus-promosi.php?id=<?php echo $promo['id']; ?>" class="btn btn-danger btn-sm"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus promosi ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">Tidak ada promosi yang tersedia.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
require 'footer.php';
?>