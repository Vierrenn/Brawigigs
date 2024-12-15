<?php
session_start();
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'brawigigs';

// Cek jika sesi tersedia
if (!isset($_SESSION['id_pendaftaran'])) {
    die("ID Pendaftaran tidak ditemukan. Silakan daftar terlebih dahulu.");
}

$id_pendaftaran = $_SESSION['id_pendaftaran'];

// Koneksi ke database
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$upload_dir = "berkas/";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['berkas'])) {
    foreach ($_FILES['berkas']['name'] as $jenis_berkas => $nama_file) {
        if (!empty($nama_file)) {
            $tmp_name = $_FILES['berkas']['tmp_name'][$jenis_berkas];
            $file_ext = pathinfo($nama_file, PATHINFO_EXTENSION);
            $nama_file_uniq = uniqid() . "_" . $nama_file;
            $path_file = $upload_dir . $nama_file_uniq;

            // Validasi file
            $allowed_ext = ['pdf', 'jpg', 'jpeg', 'png'];
            if (!in_array(strtolower($file_ext), $allowed_ext)) {
                echo "File {$nama_file} memiliki tipe tidak diizinkan.";
                continue;
            }

            // Pindahkan file ke folder uploads
            if (move_uploaded_file($tmp_name, $path_file)) {
                // Simpan metadata ke database
                $stmt = $conn->prepare(
                    "INSERT INTO berkas (id_pendaftaran, jenis_berkas, nama_file, path_file, tipe_file) 
                    VALUES (?, ?, ?, ?, ?)"
                );
                $stmt->bind_param(
                    "issss",
                    $id_pendaftaran,
                    $jenis_berkas,
                    $nama_file,
                    $path_file,
                    $file_ext
                );

                if ($stmt->execute()) {
                    echo "File {$nama_file} berhasil diunggah.<br>";
                } else {
                    echo "Gagal menyimpan file {$nama_file} ke database: " . $stmt->error;
                }
            } else {
                echo "Gagal mengunggah file {$nama_file}.";
            }
        }
    }
} else {
    echo "Tidak ada file yang diunggah.";
}

$conn->close();
?>
