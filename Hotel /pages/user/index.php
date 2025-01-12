<?php
include 'header.php';


$query = "SELECT COUNT(*) AS jumlah_promosi FROM promotions";
$stmt = $pdo->prepare($query);
$stmt->execute();
$promosi = $stmt->fetch(PDO::FETCH_ASSOC);
$ada_promosi = $promosi['jumlah_promosi'] > 0;


$query_tipe_kamar = "SELECT DISTINCT tipe_kamar FROM rooms";
$stmt = $pdo->prepare($query_tipe_kamar);
$stmt->execute();
$tipe_kamar = $stmt->fetchAll(PDO::FETCH_ASSOC);


$query_fasilitas = "SELECT fasilitas FROM rooms WHERE fasilitas IS NOT NULL";
$stmt = $pdo->prepare($query_fasilitas);
$stmt->execute();
$fasilitas_data = $stmt->fetchAll(PDO::FETCH_ASSOC);


$fasilitas_list = [];
foreach ($fasilitas_data as $row) {
  $individual_fasilitas = array_map('trim', explode(',', $row['fasilitas']));
  $fasilitas_list = array_merge($fasilitas_list, $individual_fasilitas);
}
$fasilitas_list = array_unique($fasilitas_list);
?>

<div class="container mt-5">
  <div class="text-center">
    <h1>Selamat Datang di Sistem Perhotelan</h1>
    <p class="lead">Silakan gunakan menu navigasi di atas untuk mengakses fitur.</p>
  </div>

  <!-- Notifikasi promosi -->
  <?php if ($ada_promosi): ?>
    <div class="alert alert-success text-center mt-4">
      <strong>Lagi promosi nih!</strong> Manfaatkan diskon menarik untuk kamar tertentu.
      <a href="daftar-kamar.php" class="alert-link">Lihat sekarang!</a>
    </div>
  <?php endif; ?>


  <!-- Form Reservasi -->
  <div class="card mt-4">
    <div class="card-body">
      <h4 class="card-title text-center">Reservasi Kamar</h4>
      <form action="reservasi-cari.php" method="POST">
        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="tipe_kamar" class="form-label">Tipe Kamar</label>
            <select id="tipe_kamar" name="tipe_kamar" class="form-control">
              <option value="">Semua Tipe Kamar</option>
              <?php foreach ($tipe_kamar as $tipe): ?>
                <option value="<?php echo htmlspecialchars($tipe['tipe_kamar']); ?>">
                  <?php echo htmlspecialchars($tipe['tipe_kamar']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Fasilitas</label>
          <div class="d-flex flex-wrap">
            <?php foreach ($fasilitas_list as $fasilitas): ?>
              <div class="form-check me-3">
                <input class="form-check-input" type="checkbox" name="fasilitas[]"
                  value="<?php echo htmlspecialchars($fasilitas); ?>" id="<?php echo htmlspecialchars($fasilitas); ?>">
                <label class="form-check-label" for="<?php echo htmlspecialchars($fasilitas); ?>">
                  <?php echo htmlspecialchars($fasilitas); ?>
                </label>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="text-center">
          <button type="submit" class="btn btn-primary">Cari Kamar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
include 'footer.php';
?>