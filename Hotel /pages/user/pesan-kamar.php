<?php
include 'header.php';

// Ambil data kamar berdasarkan ID dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT rooms.*, promotions.nama_promosi, promotions.tipe_diskon, promotions.jumlah_diskon 
              FROM rooms
              LEFT JOIN promotions 
              ON rooms.id = promotions.kamar_id 
              WHERE rooms.id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $id]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);


    if (!$room || $room['stok'] <= 0) {
        echo "<script>alert('Kamar tidak tersedia!'); window.location.href = 'daftar-kamar.php';</script>";
        exit;
    }

    // Hitung harga setelah diskon jika ada promosi
    $harga_awal = $room['harga'];
    $harga_diskon = $harga_awal;

    if (!empty($room['nama_promosi'])) {
        if ($room['tipe_diskon'] === 'persentase') {
            $harga_diskon = $harga_awal - ($harga_awal * $room['jumlah_diskon'] / 100);
        } elseif ($room['tipe_diskon'] === 'nominal') {
            $harga_diskon = $harga_awal - $room['jumlah_diskon'];
        }
    }
} else {
    echo "<script>alert('ID kamar tidak valid!'); window.location.href = 'daftar-kamar.php';</script>";
    exit;
}
?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Pesan Kamar</h1>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Kamar <?php echo htmlspecialchars($room['nomor_kamar']); ?></h5>
            <p class="card-text">Tipe: <?php echo htmlspecialchars($room['tipe_kamar']); ?></p>

            <!-- Tampilkan harga dengan promosi -->
            <?php if (!empty($room['nama_promosi'])): ?>
                <p class="card-text">
                    Harga Normal: <s>Rp<?php echo number_format($harga_awal, 2, ',', '.'); ?></s>
                </p>
                <p class="card-text text-success">
                    Harga Setelah Diskon: Rp<?php echo number_format($harga_diskon, 2, ',', '.'); ?>
                </p>
            <?php else: ?>
                <p class="card-text">Harga per malam: Rp<?php echo number_format($harga_awal, 2, ',', '.'); ?></p>
            <?php endif; ?>

            <p class="card-text">Fasilitas:
                <?php echo !empty($room['fasilitas']) ? htmlspecialchars($room['fasilitas']) : '-'; ?>
            </p>
            <p class="card-text"><strong>Stok Tersedia:</strong> <?php echo $room['stok']; ?></p>
        </div>
    </div>

    <form method="POST" action="proses-pesan.php">
        <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
        <input type="hidden" id="harga_per_malam" value="<?php echo $harga_diskon; ?>">

        <div class="mb-3">
            <label for="nama_pemesan" class="form-label">Nama Pemesan</label>
            <input type="text" class="form-control" id="nama_pemesan" name="nama_pemesan" required>
        </div>

        <div class="mb-3">
            <label for="tanggal_checkin" class="form-label">Tanggal Check-in</label>
            <input type="date" class="form-control" id="tanggal_checkin" name="tanggal_checkin" required>
        </div>

        <div class="mb-3">
            <label for="tanggal_checkout" class="form-label">Tanggal Check-out</label>
            <input type="date" class="form-control" id="tanggal_checkout" name="tanggal_checkout" required>
        </div>

        <div class="mb-3">
            <label for="jumlah_kamar" class="form-label">Jumlah Kamar</label>
            <input type="number" class="form-control" id="jumlah_kamar" name="jumlah_kamar" value="1" min="1"
                max="<?php echo $room['stok']; ?>" required>
        </div>

        <div class="mb-3">
            <p><strong>Total Harga:</strong> Rp<span id="total_harga">0</span></p>
        </div>

        <button type="submit" class="btn btn-primary">Pesan</button>
        <a href="daftar-kamar.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<script>
    // Ambil elemen yang dibutuhkan
    const hargaPerMalam = parseFloat(document.getElementById('harga_per_malam').value);
    const jumlahKamarInput = document.getElementById('jumlah_kamar');
    const tanggalCheckinInput = document.getElementById('tanggal_checkin');
    const tanggalCheckoutInput = document.getElementById('tanggal_checkout');
    const totalHargaElement = document.getElementById('total_harga');

    function hitungTotalHarga() {
        const jumlahKamar = parseInt(jumlahKamarInput.value);
        const checkin = new Date(tanggalCheckinInput.value);
        const checkout = new Date(tanggalCheckoutInput.value);

        if (checkin && checkout && checkout > checkin && jumlahKamar > 0) {
            const selisihHari = (checkout - checkin) / (1000 * 60 * 60 * 24); // Hitung jumlah hari
            const totalHarga = selisihHari * jumlahKamar * hargaPerMalam; // Hitung total harga
            totalHargaElement.textContent = totalHarga.toLocaleString('id-ID');
        } else {
            totalHargaElement.textContent = "0";
        }
    }

    // Event listener untuk menghitung total harga saat input berubah
    tanggalCheckinInput.addEventListener('change', hitungTotalHarga);
    tanggalCheckoutInput.addEventListener('change', hitungTotalHarga);
    jumlahKamarInput.addEventListener('input', hitungTotalHarga);
</script>

<?php
include 'footer.php';
?>