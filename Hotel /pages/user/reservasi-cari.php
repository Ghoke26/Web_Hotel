<?php
include 'header.php';
$rooms = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form pencarian
    $tipe_kamar = $_POST['tipe_kamar'];
    $fasilitas = isset($_POST['fasilitas']) ? $_POST['fasilitas'] : [];

    // Bangun query SQL untuk pencarian kamar dengan diskon
    $query = "SELECT 
                rooms.id, 
                rooms.nomor_kamar, 
                rooms.tipe_kamar, 
                rooms.harga, 
                rooms.fasilitas, 
                rooms.stok, 
                promotions.tipe_diskon, 
                promotions.jumlah_diskon 
              FROM rooms
              LEFT JOIN promotions ON promotions.kamar_id = rooms.id
              WHERE rooms.stok > 0";
    $params = [];

    // Filter berdasarkan tipe kamar jika ada
    if (!empty($tipe_kamar)) {
        $query .= " AND rooms.tipe_kamar = :tipe_kamar";
        $params['tipe_kamar'] = $tipe_kamar;
    }

    // Filter berdasarkan fasilitas
    if (!empty($fasilitas)) {
        foreach ($fasilitas as $key => $value) {
            $query .= " AND rooms.fasilitas LIKE :fasilitas$key";
            $params["fasilitas$key"] = '%' . $value . '%';
        }
    }

    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Terapkan diskon jika ada
        foreach ($rooms as &$room) {
            if (!empty($room['tipe_diskon'])) {
                if ($room['tipe_diskon'] === 'persentase') {
                    $room['harga_diskon'] = $room['harga'] - ($room['harga'] * $room['jumlah_diskon'] / 100);
                } elseif ($room['tipe_diskon'] === 'nominal') {
                    $room['harga_diskon'] = $room['harga'] - $room['jumlah_diskon'];
                }
            } else {
                $room['harga_diskon'] = $room['harga']; // Tidak ada diskon
            }
        }
    } catch (PDOException $e) {
        echo "<script>alert('Gagal mencari kamar: " . $e->getMessage() . "');</script>";
    }
}
?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Cari Reservasi Kamar</h1>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="tipe_kamar" class="form-label">Tipe Kamar</label>
            <select class="form-control" id="tipe_kamar" name="tipe_kamar">
                <option value="">Semua Tipe</option>
                <option value="Standard">Standard</option>
                <option value="Deluxe">Deluxe</option>
                <option value="Suite">Suite</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Fasilitas</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="wifi" name="fasilitas[]" value="WiFi">
                <label class="form-check-label" for="wifi">WiFi</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="tv" name="fasilitas[]" value="TV">
                <label class="form-check-label" for="tv">TV</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="ac" name="fasilitas[]" value="AC">
                <label class="form-check-label" for="ac">AC</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="breakfast" name="fasilitas[]" value="Breakfast">
                <label class="form-check-label" for="breakfast">Breakfast</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Cari Kamar</button>
    </form>
</div>

<div class="container mt-4">
    <h2 class="text-center mb-4">Hasil Pencarian</h2>
    <?php if (count($rooms) > 0): ?>
        <div class="row">
            <?php foreach ($rooms as $room): ?>
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Kamar <?php echo htmlspecialchars($room['nomor_kamar']); ?></h5>
                            <p class="card-text">Tipe: <?php echo htmlspecialchars($room['tipe_kamar']); ?></p>
                            <p class="card-text">
                                Harga:
                                <?php if (!empty($room['tipe_diskon'])): ?>
                                    <span class="text-decoration-line-through">
                                        Rp<?php echo number_format($room['harga'], 2, ',', '.'); ?>
                                    </span>
                                    <span class="text-success fw-bold">
                                        Rp<?php echo number_format($room['harga_diskon'], 2, ',', '.'); ?>
                                    </span>
                                <?php else: ?>
                                    <span>
                                        Rp<?php echo number_format($room['harga'], 2, ',', '.'); ?>
                                    </span>
                                <?php endif; ?>
                            </p>
                            <p class="card-text">Fasilitas: <?php echo htmlspecialchars($room['fasilitas']); ?></p>
                            <p class="card-text"><strong>Stok Tersedia:</strong> <?php echo $room['stok']; ?></p>
                            <a href="pesan-kamar.php?id=<?php echo $room['id']; ?>" class="btn btn-primary">Pesan</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-center">Kamar yang Anda cari tidak ditemukan.</p>
    <?php endif; ?>
</div>


<?php
include 'footer.php';
?>