<?php
include 'heading.php'; // Sertakan header (pastikan berisi koneksi database)

if (isset($_GET['action']) && isset($_GET['id'])) {
    // Escape input untuk keamanan
    $action = htmlspecialchars(trim($_GET['action']));
    $id = intval($_GET['id']); // Pastikan ID adalah integer

    try {
        if ($action === 'verifikasi') {
            // Ubah status menjadi verified
            $query = "UPDATE bookings SET status = 'verified' WHERE id = :id AND status = 'pending'";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['id' => $id]);

            if ($stmt->rowCount() > 0) {
                echo "<script>alert('Pesanan berhasil diverifikasi!'); window.location.href = 'kasir-verifikasi.php';</script>";
            } else {
                echo "<script>alert('Gagal memverifikasi pesanan. Pesanan mungkin sudah diproses atau tidak valid.'); window.location.href = 'kasir-verifikasi.php';</script>";
            }
        } elseif ($action === 'batalkan') {
            // Ambil data pesanan sebelum membatalkan
            $query = "SELECT room_id, jumlah_kamar FROM bookings WHERE id = :id AND status = 'pending'";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['id' => $id]);
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($booking) {
                // Kembalikan stok kamar sebelum membatalkan
                $query = "UPDATE rooms SET stok = stok + :jumlah_kamar WHERE id = :room_id";
                $stmt = $pdo->prepare($query);
                $stmt->execute([
                    'jumlah_kamar' => $booking['jumlah_kamar'],
                    'room_id' => $booking['room_id']
                ]);

                // Batalkan pesanan
                $query = "UPDATE bookings SET status = 'canceled' WHERE id = :id AND status = 'pending'";
                $stmt = $pdo->prepare($query);
                $stmt->execute(['id' => $id]);

                if ($stmt->rowCount() > 0) {
                    echo "<script>alert('Pesanan berhasil dibatalkan.'); window.location.href = 'kasir-verifikasi.php';</script>";
                } else {
                    echo "<script>alert('Gagal membatalkan pesanan.'); window.location.href = 'kasir-verifikasi.php';</script>";
                }
            } else {
                echo "<script>alert('Pesanan tidak ditemukan atau sudah diproses.'); window.location.href = 'kasir-verifikasi.php';</script>";
            }
        } else {
            echo "<script>alert('Aksi tidak valid.'); window.location.href = 'kasir-verifikasi.php';</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Kesalahan sistem: " . htmlspecialchars($e->getMessage()) . "'); window.location.href = 'kasir-verifikasi.php';</script>";
    }
} else {
    echo "<script>alert('Permintaan tidak valid.'); window.location.href = 'kasir-verifikasi.php';</script>";
}
?>