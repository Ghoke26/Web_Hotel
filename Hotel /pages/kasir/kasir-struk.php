<?php
include 'heading.php'; // Header kasir (jika ada)
?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Menu Cetak Struk</h1>

    <?php
    // Ambil data pembayaran dari tabel payments
    $query = "SELECT 
                payments.id AS payment_id, 
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
              ORDER BY payments.tanggal_pembayaran DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <table class="table table-bordered table-striped">
        <thead class="text-center">
            <tr>
                <th>#</th>
                <th>Nomor Kamar</th>
                <th>Tipe Kamar</th>
                <th>Nama Pemesan</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Jumlah Kamar</th>
                <th>Metode Pembayaran</th>
                <th>Total Harga</th>
                <th>Tanggal Pembayaran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($payments) > 0): ?>
                <?php foreach ($payments as $index => $payment): ?>
                    <tr>
                        <td class="text-center"><?php echo $index + 1; ?></td>
                        <td class="text-center"><?php echo $payment['nomor_kamar']; ?></td>
                        <td><?php echo $payment['tipe_kamar']; ?></td>
                        <td><?php echo $payment['nama_pemesan']; ?></td>
                        <td class="text-center"><?php echo $payment['tanggal_checkin']; ?></td>
                        <td class="text-center"><?php echo $payment['tanggal_checkout']; ?></td>
                        <td class="text-center"><?php echo $payment['jumlah_kamar']; ?></td>
                        <td class="text-center"><?php echo ucfirst($payment['metode_pembayaran']); ?></td>
                        <td class="text-center">Rp<?php echo number_format($payment['total_harga'], 2, ',', '.'); ?></td>
                        <td class="text-center"><?php echo $payment['tanggal_pembayaran']; ?></td>
                        <td class="text-center">
                            <a href="kasir-cetak-struk.php?id=<?php echo $payment['payment_id']; ?>"
                                class="btn btn-info btn-sm">Cetak Struk</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="11" class="text-center">Tidak ada pembayaran yang tercatat.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="index.php" class="btn btn-secondary no-print">Kembali</a>
</div>

<?php
include 'footer.php';
?>