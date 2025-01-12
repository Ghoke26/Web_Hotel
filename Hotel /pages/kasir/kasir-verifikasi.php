<?php
include 'heading.php';

// Ambil semua pemesanan dengan status pending
$query = "SELECT bookings.id, bookings.nama_pemesan, bookings.tanggal_checkin, bookings.tanggal_checkout, 
                 bookings.total_harga, bookings.status, rooms.nomor_kamar, rooms.tipe_kamar 
          FROM bookings
          INNER JOIN rooms ON bookings.room_id = rooms.id
          WHERE bookings.status = 'pending'
          ORDER BY bookings.id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Verifikasi Pemesanan</h1>

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
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($bookings) > 0): ?>
                <?php foreach ($bookings as $index => $booking): ?>
                    <tr>
                        <td class="text-center"><?php echo $index + 1; ?></td>
                        <td class="text-center"><?php echo htmlspecialchars($booking['nomor_kamar']); ?></td>
                        <td><?php echo htmlspecialchars($booking['tipe_kamar']); ?></td>
                        <td><?php echo htmlspecialchars($booking['nama_pemesan']); ?></td>
                        <td class="text-center"><?php echo htmlspecialchars($booking['tanggal_checkin']); ?></td>
                        <td class="text-center"><?php echo htmlspecialchars($booking['tanggal_checkout']); ?></td>
                        <td class="text-center">Rp<?php echo number_format($booking['total_harga'], 2, ',', '.'); ?></td>
                        <td class="text-center">
                            <a href="kasir-proses.php?action=verifikasi&id=<?php echo $booking['id']; ?>"
                                class="btn btn-success btn-sm">Konfirmasi</a>
                            <a href="kasir-proses.php?action=batalkan&id=<?php echo $booking['id']; ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">Batalkan</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">Tidak ada pemesanan yang perlu diverifikasi.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Tombol Kembali -->
    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </div>
</div>



<?php
include 'footer.php';
?>