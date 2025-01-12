<?php
require '../../include/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $query = "DELETE FROM rooms WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $id]);

        echo "<script>alert('Kamar berhasil dihapus!'); window.location.href = 'data-kamar.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Gagal menghapus kamar: " . $e->getMessage() . "'); window.location.href = 'data-kamar.php';</script>";
    }
} else {
    echo "<script>alert('ID kamar tidak valid!'); window.location.href = 'data-kamar.php';</script>";
}
?>