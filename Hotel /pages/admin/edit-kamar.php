<?php
require 'header.php'; 

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT * FROM rooms WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $id]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$room) {
        echo "<script>alert('Data kamar tidak ditemukan!'); window.location.href = 'data-kamar.php';</script>";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nomor = $_POST['nomor'];
    $tipe = $_POST['tipe'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok']; // Tambahkan stok
    $fasilitas = isset($_POST['fasilitas']) ? implode(', ', $_POST['fasilitas']) : ''; 

    try {
        $query = "UPDATE rooms 
                  SET nomor_kamar = :nomor, 
                      tipe_kamar = :tipe, 
                      harga = :harga, 
                      stok = :stok, 
                      fasilitas = :fasilitas 
                  WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'id' => $id,
            'nomor' => $nomor,
            'tipe' => $tipe,
            'harga' => $harga,
            'stok' => $stok,
            'fasilitas' => $fasilitas
        ]);

        echo "<script>alert('Kamar berhasil diperbarui!'); window.location.href = 'data-kamar.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Gagal memperbarui kamar: " . $e->getMessage() . "');</script>";
    }
}
?>

<div class="container mt-4">
    <h1 class="text-center">Edit Kamar</h1>
    <form action="" method="POST">
    <input type="hidden" name="id" value="<?php echo $room['id']; ?>">

        <div class="mb-3">
            <label for="nomor" class="form-label">Nomor Kamar</label>
            <input type="text" class="form-control" id="nomor" name="nomor" value="<?php echo $room['nomor_kamar']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="tipe" class="form-label">Tipe Kamar</label>
            <select class="form-control" id="tipe" name="tipe" required>
                <option value="Standard" <?php echo $room['tipe_kamar'] == 'Standard' ? 'selected' : ''; ?>>Standard</option>
                <option value="Deluxe" <?php echo $room['tipe_kamar'] == 'Deluxe' ? 'selected' : ''; ?>>Deluxe</option>
                <option value="Suite" <?php echo $room['tipe_kamar'] == 'Suite' ? 'selected' : ''; ?>>Suite</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Fasilitas</label><br>
            <?php
            $selected_fasilitas = explode(', ', $room['fasilitas']);
            ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="wifi" name="fasilitas[]" value="WiFi" 
                    <?php echo in_array('WiFi', $selected_fasilitas) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="wifi">WiFi</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="tv" name="fasilitas[]" value="TV" 
                    <?php echo in_array('TV', $selected_fasilitas) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="tv">TV</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="ac" name="fasilitas[]" value="AC" 
                    <?php echo in_array('AC', $selected_fasilitas) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="ac">AC</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="breakfast" name="fasilitas[]" value="Breakfast" 
                    <?php echo in_array('Breakfast', $selected_fasilitas) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="breakfast">Breakfast</label>
            </div>
        </div>

        <div class="mb-3">
            <label for="harga" class="form-label">Harga (per malam)</label>
            <input type="number" class="form-control" id="harga" name="harga" value="<?php echo $room['harga']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="stok" class="form-label">Jumlah Stok</label>
            <input type="number" class="form-control" id="stok" name="stok" value="<?php echo $room['stok']; ?>" min="0" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="data-kamar.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php
require 'footer.php'; 
?>
