<?php
include 'heading.php';


$query = "SELECT bookings.id, bookings.nama_pemesan, bookings.tanggal_checkin, bookings.tanggal_checkout, 
                 bookings.total_harga, rooms.nomor_kamar, rooms.tipe_kamar 
          FROM bookings
          INNER JOIN rooms ON bookings.room_id = rooms.id
          WHERE bookings.status = 'verified'
          ORDER BY bookings.id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Proses Pembayaran</h1>

    <table class="table table-bordered table-striped">
        <thead class="text-center">
            <tr>
                <th>#</th>
                <th>Nama Pemesan</th>
                <th>Nomor Kamar</th>
                <th>Tipe Kamar</th>
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
                        <td><?php echo htmlspecialchars($booking['nama_pemesan']); ?></td>
                        <td class="text-center"><?php echo htmlspecialchars($booking['nomor_kamar']); ?></td>
                        <td><?php echo htmlspecialchars($booking['tipe_kamar']); ?></td>
                        <td class="text-center"><?php echo htmlspecialchars($booking['tanggal_checkin']); ?></td>
                        <td class="text-center"><?php echo htmlspecialchars($booking['tanggal_checkout']); ?></td>
                        <td class="text-center">Rp<?php echo number_format($booking['total_harga'], 2, ',', '.'); ?></td>
                        <td class="text-center">
                            <!-- Tombol Proses Pembayaran -->
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#paymentModal-<?php echo $booking['id']; ?>">Proses Pembayaran</button>

                            <!-- Modal Pembayaran -->
                            <div class="modal fade" id="paymentModal-<?php echo $booking['id']; ?>" tabindex="-1"
                                aria-labelledby="paymentModalLabel-<?php echo $booking['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="paymentModalLabel-<?php echo $booking['id']; ?>">Proses
                                                Pembayaran</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Nama Pemesan:</strong>
                                                <?php echo htmlspecialchars($booking['nama_pemesan']); ?></p>
                                            <p><strong>Nomor Kamar:</strong>
                                                <?php echo htmlspecialchars($booking['nomor_kamar']); ?></p>
                                            <p><strong>Tipe Kamar:</strong>
                                                <?php echo htmlspecialchars($booking['tipe_kamar']); ?></p>
                                            <p><strong>Check-in:</strong>
                                                <?php echo htmlspecialchars($booking['tanggal_checkin']); ?></p>
                                            <p><strong>Check-out:</strong>
                                                <?php echo htmlspecialchars($booking['tanggal_checkout']); ?></p>
                                            <p><strong>Total Harga:</strong>
                                                Rp<?php echo number_format($booking['total_harga'], 2, ',', '.'); ?></p>

                                            <!-- Form Pilihan Metode Pembayaran -->
                                            <form method="POST" action="kasir-proses-pembayaran.php">
                                                <input type="hidden" name="id" value="<?php echo $booking['id']; ?>">
                                                <div class="mb-3">
                                                    <label for="paymentMethod" class="form-label">Metode Pembayaran</label>
                                                    <select class="form-select" id="paymentMethod" name="payment_method"
                                                        required>
                                                        <option value="cash">Tunai</option>
                                                        <option value="dana">Dana</option>
                                                        <option value="transfer">Transfer Bank</option>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-success">Bayar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal Pembayaran -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">Tidak ada pesanan yang siap untuk pembayaran.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<?php
include 'footer.php';
?>