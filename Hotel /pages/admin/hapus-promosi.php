<?php
require '../../include/koneksi.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    try {
        $query = "DELETE FROM promotions WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $id]);

        echo "<script>alert('Promosi berhasil dihapus!'); window.location.href = 'promosi.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Gagal menghapus promosi: " . $e->getMessage() . "'); window.location.href = 'promosi.php';</script>";
    }
} else {
    echo "<script>alert('ID promosi tidak valid!'); window.location.href = 'promosi.php';</script>";
}
?>