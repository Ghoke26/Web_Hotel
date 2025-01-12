<?php
require 'header.php';

$bulan = isset($_GET['bulan']) ? intval($_GET['bulan']) : date('m');
$tahun = isset($_GET['tahun']) ? intval($_GET['tahun']) : date('Y');

$query = "SELECT 
            payments.id AS payment_id, 
            payments.tanggal_pembayaran, 
            bookings.nama_pemesan, 
            bookings.tanggal_checkin, 
            bookings.tanggal_checkout, 
            bookings.total_harga, 
            rooms.nomor_kamar, 
            rooms.tipe_kamar 
          FROM payments
          INNER JOIN bookings ON payments.booking_id = bookings.id
          INNER JOIN rooms ON bookings.room_id = rooms.id
          WHERE MONTH(payments.tanggal_pembayaran) = :bulan AND YEAR(payments.tanggal_pembayaran) = :tahun
          ORDER BY payments.tanggal_pembayaran ASC";
$stmt = $pdo->prepare($query);
$stmt->execute(['bulan' => $bulan, 'tahun' => $tahun]);
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalPendapatan = array_sum(array_column($payments, 'total_harga'));
?>

<div class="container mt-4">
    <a href="index.php" class="btn btn-secondary mb-3">Kembali</a>
    <h1 class="text-center mb-5">Laporan Pembayaran Bulanan</h1>

    <form method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-5">
                <label for="bulan" class="form-label">Bulan</label>
                <select name="bulan" id="bulan" class="form-control">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo $i == $bulan ? 'selected' : ''; ?>>
                            <?php echo date('F', mktime(0, 0, 0, $i, 1)); ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-5">
                <label for="tahun" class="form-label">Tahun</label>
                <select name="tahun" id="tahun" class="form-control">
                    <?php for ($i = date('Y') - 5; $i <= date('Y'); $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo $i == $tahun ? 'selected' : ''; ?>>
                            <?php echo $i; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-2 align-self-end">
                <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
            </div>
        </div>
    </form>

    <b class="p-1 bg-success text-white">Total Keseluruhan:
        <span>Rp<?php echo number_format($totalPendapatan, 2, ',', '.'); ?></span>
    </b>

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
                <th>Tanggal Pembayaran</th>
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
                        <td class="text-center">Rp<?php echo number_format($payment['total_harga'], 2, ',', '.'); ?></td>
                        <td class="text-center"><?php echo $payment['tanggal_pembayaran']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">Tidak ada pembayaran pada bulan ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
require 'footer.php';
?>