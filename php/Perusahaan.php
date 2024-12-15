<?php
include "../php/connect.php";
// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pendaftaran = $_POST['id_pendaftaran'];
    $status = $_POST['status']; 
    $kalimat_pengumuman = $_POST['kalimat_pengumuman'];

    // Menyiapkan query untuk menyimpan pengumuman
    $stmt = $conn->prepare("INSERT INTO pengumuman (id_pendaftaran, status, kalimat_pengumuman) 
                            VALUES (?, ?, ?) 
                            ON DUPLICATE KEY UPDATE status = VALUES(status), kalimat_pengumuman = VALUES(kalimat_pengumuman)");
    $stmt->bind_param("iss", $id_pendaftaran, $status, $kalimat_pengumuman);
    
    if ($stmt->execute()) {
        echo "Pengumuman berhasil disimpan.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
