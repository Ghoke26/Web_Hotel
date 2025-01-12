<?php
include 'heading.php';
date_default_timezone_set('Asia/Jakarta');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Pastikan ID adalah angka

    // Ambil data pembayaran berdasarkan ID dari tabel payments
    $query = "SELECT 
                payments.tanggal_pembayaran, 
                payments.metode_pembayaran,
                bookings.nama_pemesan, 
                bookings.tanggal_checkin, 
                bookings.tanggal_checkout, 
                bookings.total_harga, 
                bookings.jumlah_kamar,
                rooms.nomor_kamar, 
                rooms.tipe_kamar
              FROM payments
              INNER JOIN bookings ON payments.booking_id = bookings.id
              INNER JOIN rooms ON bookings.room_id = rooms.id
              WHERE payments.id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $id]);
    $payment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$payment) {
        echo "<script>alert('Struk tidak valid atau data tidak ditemukan!'); window.location.href = 'kasir-struk.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('ID pembayaran tidak valid!'); window.location.href = 'kasir-struk.php';</script>";
    exit;
}
?>


<style>
    @media print {
        .no-print {
            display: none !important;
        }
    }
</style>

<div class="container mt-4">
    <h1 class="text-center mb-4">Struk Pembayaran</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Detail Pembayaran</h5>
            <p><strong>Nomor Kamar:</strong> <?php echo $payment['nomor_kamar']; ?></p>
            <p><strong>Tipe Kamar:</strong> <?php echo $payment['tipe_kamar']; ?></p>
            <p><strong>Nama Pemesan:</strong> <?php echo $payment['nama_pemesan']; ?></p>
            <p><strong>Check-in:</strong> <?php echo $payment['tanggal_checkin']; ?></p>
            <p><strong>Check-out:</strong> <?php echo $payment['tanggal_checkout']; ?></p>
            <p><strong>Jumlah Kamar:</strong> <?php echo $payment['jumlah_kamar']; ?></p>
            <p><strong>Total Harga:</strong> Rp<?php echo number_format($payment['total_harga'], 2, ',', '.'); ?></p>
            <p><strong>Metode Pembayaran:</strong> <?php echo ucfirst($payment['metode_pembayaran']); ?></p>
            <p><strong>Tanggal Pembayaran:</strong> <?php echo $payment['tanggal_pembayaran']; ?></p>
            <p><strong>Tanggal Cetak:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
        </div>
    </div>
    <div class="text-center mt-4">
        <button onclick="window.print()" class="btn btn-primary no-print">Cetak Struk</button>
        <a href="kasir-struk.php" class="btn btn-secondary no-print">Kembali</a>
    </div>
</div>

<?php
include "footer.php";
?>