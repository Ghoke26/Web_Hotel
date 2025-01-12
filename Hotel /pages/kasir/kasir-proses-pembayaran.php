<?php
include 'heading.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $metode_pembayaran = htmlspecialchars($_POST['payment_method']); // Ambil metode pembayaran

    // Ambil data pesanan berdasarkan ID
    $query = "SELECT * FROM bookings WHERE id = :id AND status = 'verified'";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$booking) {
        echo "<script>alert('Pesanan tidak valid atau sudah diproses!'); window.location.href = 'kasir-pembayaran.php';</script>";
        exit;
    }

    // Mulai proses pembayaran
    try {
        // Mulai transaksi
        $pdo->beginTransaction();

        // Tambahkan data ke tabel payments
        $query = "INSERT INTO payments (booking_id, metode_pembayaran, tanggal_pembayaran) 
                  VALUES (:booking_id, :metode_pembayaran, NOW())";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'booking_id' => $id,
            'metode_pembayaran' => $metode_pembayaran
        ]);

        // Update status di tabel bookings menjadi 'confirmed'
        $query = "UPDATE bookings SET status = 'confirmed' WHERE id = :id AND status = 'verified'";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $id]);

        if ($stmt->rowCount() > 0) {
            // Commit transaksi jika berhasil
            $pdo->commit();
            echo "<script>alert('Pembayaran berhasil diproses!'); window.location.href = 'kasir-pembayaran.php';</script>";
        } else {
            // Rollback jika query gagal
            $pdo->rollBack();
            echo "<script>alert('Gagal memproses pembayaran. Pesanan mungkin sudah diproses.'); window.location.href = 'kasir-pembayaran.php';</script>";
        }
    } catch (PDOException $e) {
        // Rollback jika ada error
        $pdo->rollBack();
        echo "<script>alert('Gagal memproses pembayaran: " . htmlspecialchars($e->getMessage()) . "'); window.location.href = 'kasir-pembayaran.php';</script>";
    }
} else {
    // Jika metode tidak valid
    echo "<script>alert('Permintaan tidak valid!'); window.location.href = 'kasir-pembayaran.php';</script>";
}
?>