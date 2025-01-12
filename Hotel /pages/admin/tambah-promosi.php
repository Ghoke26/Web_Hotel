<?php
require 'header.php';

$query = "SELECT id, nomor_kamar FROM rooms";
$stmt = $pdo->prepare($query);
$stmt->execute();
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_promosi = $_POST['nama_promosi'];
    $tipe_diskon = $_POST['tipe_diskon'];
    $jumlah_diskon = $_POST['jumlah_diskon'];
    $kamar_id = $_POST['kamar_id'] ?: null;

    try {
        $query = "INSERT INTO promotions (nama_promosi, tipe_diskon, jumlah_diskon, kamar_id) 
                  VALUES (:nama_promosi, :tipe_diskon, :jumlah_diskon, :kamar_id)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'nama_promosi' => $nama_promosi,
            'tipe_diskon' => $tipe_diskon,
            'jumlah_diskon' => $jumlah_diskon,
            'kamar_id' => $kamar_id,
        ]);

        echo "<script>alert('Promosi berhasil ditambahkan!'); window.location.href = 'promosi.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Gagal menambahkan promosi: " . $e->getMessage() . "');</script>";
    }
}
?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Tambah Promosi</h1>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="nama_promosi" class="form-label">Nama Promosi</label>
            <input type="text" class="form-control" id="nama_promosi" name="nama_promosi" required>
        </div>
        <div class="mb-3">
            <label for="tipe_diskon" class="form-label">Tipe Diskon</label>
            <select class="form-control" id="tipe_diskon" name="tipe_diskon" required>
                <option value="persentase">Persentase</option>
                <option value="nominal">Nominal</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="jumlah_diskon" class="form-label">Jumlah Diskon</label>
            <input type="number" class="form-control" id="jumlah_diskon" name="jumlah_diskon" min="1" step="0.01"
                required>
        </div>
        <div class="mb-3">
            <label for="kamar_id" class="form-label">Kamar (Opsional)</label>
            <select class="form-control" id="kamar_id" name="kamar_id">
                <option value="">Semua Kamar</option>
                <?php foreach ($rooms as $room): ?>
                    <option value="<?php echo $room['id']; ?>"><?php echo $room['nomor_kamar']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Tambah</button>
        <a href="promosi.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php
require 'footer.php';
?>