<?php
require '../../include/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id = intval($_POST['room_id']);
    $nama_pemesan = htmlspecialchars(trim($_POST['nama_pemesan']));
    $tanggal_checkin = $_POST['tanggal_checkin'];
    $tanggal_checkout = $_POST['tanggal_checkout'];
    $jumlah_kamar = intval($_POST['jumlah_kamar']);

    // Ambil data kamar dan promosi jika ada
    $query = "SELECT rooms.*, promotions.tipe_diskon, promotions.jumlah_diskon 
              FROM rooms
              LEFT JOIN promotions 
              ON rooms.id = promotions.kamar_id 
              WHERE rooms.id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $room_id]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$room) {
        echo "<script>alert('Kamar tidak ditemukan!'); window.location.href = 'daftar-kamar.php';</script>";
        exit;
    }

    if ($room['stok'] < $jumlah_kamar) {
        echo "<script>alert('Stok kamar tidak mencukupi!'); window.location.href = 'pesan-kamar.php?id={$room_id}';</script>";
        exit; 
    }

    // Hitung jumlah hari pemesanan
    $checkin = new DateTime($tanggal_checkin);
    $checkout = new DateTime($tanggal_checkout);
    $selisih_hari = $checkout->diff($checkin)->days;

    if ($selisih_hari <= 0) {
        echo "<script>alert('Tanggal check-out harus lebih dari tanggal check-in!'); window.location.href = 'pesan-kamar.php?id={$room_id}';</script>";
        exit;
    }

    // Hitung harga awal
    $harga_awal = $room['harga'];
    $harga_diskon = $harga_awal;

    // Hitung harga dengan diskon jika ada promosi
    if (!empty($room['tipe_diskon'])) {
        if ($room['tipe_diskon'] === 'persentase') {
            $harga_diskon = $harga_awal - ($harga_awal * $room['jumlah_diskon'] / 100);
        } elseif ($room['tipe_diskon'] === 'nominal') {
            $harga_diskon = $harga_awal - $room['jumlah_diskon'];
        }
    }

    // Hitung total harga
    $total_harga = $selisih_hari * $harga_diskon * $jumlah_kamar;

    try {
        // Mulai transaksi
        $pdo->beginTransaction();

        // Masukkan data pemesanan ke tabel bookings
        $query = "INSERT INTO bookings (room_id, nama_pemesan, tanggal_checkin, tanggal_checkout, total_harga, jumlah_kamar) 
                  VALUES (:room_id, :nama_pemesan, :tanggal_checkin, :tanggal_checkout, :total_harga, :jumlah_kamar)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'room_id' => $room_id,
            'nama_pemesan' => $nama_pemesan,
            'tanggal_checkin' => $tanggal_checkin,
            'tanggal_checkout' => $tanggal_checkout,
            'total_harga' => $total_harga,
            'jumlah_kamar' => $jumlah_kamar
        ]);

        // Kurangi stok kamar
        $query = "UPDATE rooms SET stok = stok - :jumlah_kamar WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'id' => $room_id,
            'jumlah_kamar' => $jumlah_kamar
        ]);

        // Commit transaksi
        $pdo->commit();

        echo "<script>alert('Pemesanan berhasil! Total harga: Rp" . number_format($total_harga, 2, ',', '.') . "'); window.location.href = 'daftar-kamar.php';</script>";
    } catch (PDOException $e) {
        // Rollback jika terjadi kesalahan
        $pdo->rollBack();
        echo "<script>alert('Gagal memproses pemesanan: " . $e->getMessage() . "'); window.location.href = 'pesan-kamar.php?id={$room_id}';</script>";
    }
} else {
    echo "<script>alert('Permintaan tidak valid!'); window.location.href = 'daftar-kamar.php';</script>";
}
?>