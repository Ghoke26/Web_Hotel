<?php
include 'header.php';

$query = "SELECT 
            rooms.*, 
            promotions.nama_promosi, 
            promotions.tipe_diskon, 
            promotions.jumlah_diskon 
          FROM rooms
          LEFT JOIN promotions 
          ON rooms.id = promotions.kamar_id
          WHERE rooms.stok > 0";
$stmt = $pdo->prepare($query);
$stmt->execute();
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<div class="container mt-4">
    <h1 class="text-center">Daftar Kamar Tersedia</h1>
    <div class="row">
        <?php if (count($rooms) > 0): ?>
            <?php foreach ($rooms as $room): ?>
                <div class="col-md-4 mb-3 d-flex align-items-stretch">
                    <div class="card shadow-sm w-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Kamar <?php echo htmlspecialchars($room['nomor_kamar']); ?></h5>
                            <p class="card-text">Tipe: <?php echo htmlspecialchars($room['tipe_kamar']); ?></p>

                            <!-- Logika untuk menampilkan promosi -->
                            <?php
                            $harga_awal = $room['harga'];
                            $harga_diskon = $harga_awal;

                            if (!empty($room['nama_promosi'])) {
                                if ($room['tipe_diskon'] === 'persentase') {
                                    $harga_diskon = $harga_awal - ($harga_awal * $room['jumlah_diskon'] / 100);
                                    echo "<p class='card-text text-danger'>Promosi: Diskon {$room['jumlah_diskon']}%</p>";
                                } elseif ($room['tipe_diskon'] === 'nominal') {
                                    $harga_diskon = $harga_awal - $room['jumlah_diskon'];
                                    echo "<p class='card-text text-danger'>Promosi: Diskon Rp" . number_format($room['jumlah_diskon'], 2, ',', '.') . "</p>";
                                }
                                // Tampilkan harga normal yang dicoret jika ada promosi
                                echo "<p class='card-text'>Harga Normal: <s>Rp" . number_format($harga_awal, 2, ',', '.') . "</s></p>";
                            }
                            ?>

                            <!-- Harga final -->
                            <p class="card-text"><strong>Harga:</strong>
                                Rp<?php echo number_format($harga_diskon, 2, ',', '.'); ?></p>

                            <!-- Fasilitas -->
                            <p class="card-text">Fasilitas:
                                <?php echo !empty($room['fasilitas']) ? htmlspecialchars($room['fasilitas']) : '-'; ?>
                            </p>
                            <p class="card-text"><strong>Stok Tersedia:</strong> <?php echo $room['stok']; ?></p>

                            <div class="mt-auto">
                                <a href="pesan-kamar.php?id=<?php echo $room['id']; ?>" class="btn btn-primary">Pesan</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="text-center">Tidak ada kamar yang tersedia saat ini.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
include 'footer.php';
?>