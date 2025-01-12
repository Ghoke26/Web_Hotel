<?php
require 'header.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM promotions WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $id]);
    $promo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$promo) {
        echo "<script>alert('Promosi tidak ditemukan!'); window.location.href = 'promosi.php';</script>";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_promosi = htmlspecialchars(trim($_POST['nama_promosi']));
    $tipe_diskon = $_POST['tipe_diskon'];
    $jumlah_diskon = $_POST['jumlah_diskon'];
    $kamar_id = $_POST['kamar_id'] ?: null;

    try {
        $query = "UPDATE promotions 
                  SET nama_promosi = :nama_promosi, tipe_diskon = :tipe_diskon, jumlah_diskon = :jumlah_diskon, 
                      kamar_id = :kamar_id 
                  WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'id' => $id,
            'nama_promosi' => $nama_promosi,
            'tipe_diskon' => $tipe_diskon,
            'jumlah_diskon' => $jumlah_diskon,
            'kamar_id' => $kamar_id,
        ]);

        echo "<script>alert('Promosi berhasil diperbarui!'); window.location.href = 'promosi.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Gagal memperbarui promosi: " . $e->getMessage() . "');</script>";
    }
}
?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Edit Promosi</h1>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="nama_promosi" class="form-label">Nama Promosi</label>
            <input type="text" class="form-control" id="nama_promosi" name="nama_promosi"
                value="<?php echo $promo['nama_promosi']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="tipe_diskon" class="form-label">Tipe Diskon</label>
            <select class="form-control" id="tipe_diskon" name="tipe_diskon" required>
                <option value="persentase" <?php echo $promo['tipe_diskon'] === 'persentase' ? 'selected' : ''; ?>>
                    Persentase</option>
                <option value="nominal" <?php echo $promo['tipe_diskon'] === 'nominal' ? 'selected' : ''; ?>>Nominal
                </option>
            </select>
        </div>
        <div class="mb-3">
            <label for="jumlah_diskon" class="form-label">Jumlah Diskon</label>
            <input type="number" class="form-control" id="jumlah_diskon" name="jumlah_diskon"
                value="<?php echo $promo['jumlah_diskon']; ?>" min="1" step="0.01" required>
        </div>
        <div class="mb-3">
            <label for="kamar_id" class="form-label">Kamar (Opsional)</label>
            <select class="form-control" id="kamar_id" name="kamar_id">
                <option value="">Semua Kamar</option>
                <?php
                $query = "SELECT id, nomor_kamar FROM rooms";
                $stmt = $pdo->prepare($query);
                $stmt->execute();
                $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rooms as $room): ?>
                    <option value="<?php echo $room['id']; ?>" <?php echo $promo['kamar_id'] == $room['id'] ? 'selected' : ''; ?>>
                        <?php echo $room['nomor_kamar']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="promosi.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php
require 'footer.php';
?>