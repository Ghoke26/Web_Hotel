<?php
include 'header.php';

// Ambil semua data pemesanan
$query = "SELECT bookings.id, bookings.total_harga, bookings.nama_pemesan, 
                 bookings.tanggal_checkin, bookings.tanggal_checkout, bookings.status, 
                 rooms.nomor_kamar, rooms.tipe_kamar 
          FROM bookings
          INNER JOIN rooms ON bookings.room_id = rooms.id
          ORDER BY bookings.id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container mt-4">
    <h1 class="text-center mb-4">Riwayat Pemesanan</h1>
    <table class="table table-bordered table-striped">
        <thead class="text-center">
            <tr>
                <th>#</th>
                <th>Nomor Kamar</th>
                <th>Tipe Kamar</th>
                <th>Nama Pemesan</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Total Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($bookings) > 0): ?>
                <?php foreach ($bookings as $index => $booking): ?>
                    <tr>
                        <td class="text-center"><?php echo $index + 1; ?></td>
                        <td class="text-center"><?php echo $booking['nomor_kamar']; ?></td>
                        <td><?php echo $booking['tipe_kamar']; ?></td>
                        <td><?php echo $booking['nama_pemesan']; ?></td>
                        <td class="text-center"><?php echo $booking['tanggal_checkin']; ?></td>
                        <td class="text-center"><?php echo $booking['tanggal_checkout']; ?></td>
                        <td class="text-center">Rp<?php echo number_format($booking['total_harga'], 2, ',', '.'); ?></td>
                        <td class="text-center">
                            <?php
                            // Menampilkan status pemesanan dengan label
                            if ($booking['status'] === 'pending') {
                                echo '<span class="badge bg-primary">Menunggu Konfirmasi</span>';
                            } elseif ($booking['status'] === 'verified') {
                                echo '<span class="badge bg-warning text-dark">Menunggu Pembayaran</span>';
                            } elseif ($booking['status'] === 'confirmed') {
                                echo '<span class="badge bg-success">Transaksi Berhasil</span>';
                            } elseif ($booking['status'] === 'canceled') {
                                echo '<span class="badge bg-danger">Dibatalkan</span>';
                            } else {
                                echo '<span class="badge bg-secondary">Tidak Diketahui</span>';
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">Belum ada pemesanan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
include 'footer.php';
?>