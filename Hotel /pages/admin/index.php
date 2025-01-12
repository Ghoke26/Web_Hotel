<?php
require 'header.php';
?>

<!-- Content -->
<div class="container mt-4">
  <h1 class="text-center mb-4">Admin Dashboard</h1>

  <div class="row">
    <!-- Data Kamar -->
    <div class="col-md-4 mb-4">
      <div class="card shadow">
        <div class="card-body text-center">
          <h5 class="card-title">Data Kamar</h5>
          <p class="card-text">Mengelola data kamar</p>
          <a href="data-kamar.php" class="btn btn-primary">Kelola Kamar</a>
        </div>
      </div>
    </div>

    <!-- Kelola Promosi -->
    <div class="col-md-4 mb-4">
      <div class="card shadow">
        <div class="card-body text-center">
          <h5 class="card-title">Kelola Promosi</h5>
          <p class="card-text">Atur diskon dan promosi</p>
          <a href="promosi.php" class="btn btn-success">Kelola Promosi</a>
        </div>
      </div>
    </div>

    <!-- Laporan Pemesanan Bulanan -->
    <div class="col-md-4 mb-4">
      <div class="card shadow">
        <div class="card-body text-center">
          <h5 class="card-title">Laporan Pemesanan Bulanan</h5>
          <p class="card-text">Lihat data pemesanan bulanan</p>
          <a href="laporan-bulanan.php" class="btn btn-info">Lihat Laporan</a>
        </div>
      </div>
    </div>

  </div>
</div>





<?php
require 'footer.php';
?>