<?php
include "heading.php";
?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Dashboard Kasir</h1>
    <div class="row">
        <!-- Verifikasi Pemesanan -->
        <div class="col-md-6">
            <div class="card shadow-sm border-primary">
                <div class="card-body text-center">
                    <h5 class="card-title">Verifikasi Pemesanan</h5>
                    <p class="card-text">Lihat dan verifikasi pemesanan pelanggan</p>
                    <a href="kasir-verifikasi.php" class="btn btn-primary">Verifikasi</a>
                </div>
            </div>
        </div>

        <!-- Proses Pembayaran -->
        <div class="col-md-6">
            <div class="card shadow-sm border-success">
                <div class="card-body text-center">
                    <h5 class="card-title">Proses Pembayaran</h5>
                    <p class="card-text">Proses pembayaran pelanggan yang sudah diverifikasi</p>
                    <a href="kasir-pembayaran.php" class="btn btn-success">Proses Pembayaran</a>
                </div>
            </div>
        </div>

        <!-- Cetak Struk -->
        <div class="col-md-6 mt-4">
            <div class="card shadow-sm border-info">
                <div class="card-body text-center">
                    <h5 class="card-title">Cetak Struk</h5>
                    <p class="card-text">Cetak struk pembayaran pelanggan</p>
                    <a href="kasir-struk.php" class="btn btn-info">Cetak Struk</a>
                </div>
            </div>
        </div>
    </div>


</div>

<?php
include "footer.php";
?>