<?php
require 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomor = $_POST['nomor'];
    $tipe = $_POST['tipe'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    // Ambil fasilitas yang dipilih
    $fasilitas = isset($_POST['fasilitas']) ? implode(', ', $_POST['fasilitas']) : '';

    try {
        $query = "INSERT INTO rooms (nomor_kamar, tipe_kamar, harga, fasilitas, stok) VALUES (:nomor, :tipe, :harga, :fasilitas, :stok)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'nomor' => $nomor,
            'tipe' => $tipe,
            'harga' => $harga,
            'fasilitas' => $fasilitas,
            'stok' => $stok
        ]);

        echo "<script>alert('Kamar berhasil ditambahkan!'); window.location.href = 'data-kamar.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Gagal menambahkan kamar: " . $e->getMessage() . "');</script>";
    }
}
?>

<div class="container mt-4">
    <h1 class="text-center">Tambah Kamar Baru</h1>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="nomor" class="form-label">Nomor Kamar</label>
            <input type="text" class="form-control" id="nomor" name="nomor" required>
        </div>

        <div class="mb-3">
            <label for="tipe" class="form-label">Tipe Kamar</label>
            <select class="form-control" id="tipe" name="tipe" required>
                <option value="Standard">Standard</option>
                <option value="Deluxe">Deluxe</option>
                <option value="Suite">Suite</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="harga" class="form-label">Harga (per malam)</label>
            <input type="number" class="form-control" id="harga" name="harga" required>
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

        <div class="mb-3">
            <label for="stok" class="form-label">Jumlah Stok</label>
            <input type="number" class="form-control" id="stok" name="stok" value="<?php echo $room['stok']; ?>" min="0"
                required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="data-kamar.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>


<?php
require 'footer.php';
?>