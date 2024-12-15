<?php
include 'connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pendaftaran = $_POST['id_pendaftaran'];
    $berkas = $_FILES['berkas'];

    $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
    $uploadDir = "../berkas/$id_pendaftaran/";

    // Buat folder jika belum ada
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    foreach ($berkas['name'] as $jenisBerkas => $fileName) {
        if (!empty($fileName)) {
            $fileTmp = $berkas['tmp_name'][$jenisBerkas];
            $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Validasi tipe file
            if (!in_array($fileType, $allowedExtensions)) {
                echo "<script>alert('Tipe file tidak valid untuk $jenisBerkas!');</script>";
                continue;
            }

            // Tentukan path file
            $filePath = $uploadDir . $jenisBerkas . '.' . $fileType;

            // Simpan file ke folder upload
            if (move_uploaded_file($fileTmp, $filePath)) {
                // Simpan informasi ke database
                $query = "INSERT INTO berkas (id_pendaftaran, jenis_berkas, nama_file, path_file, tipe_file) 
                          VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("issss", $id_pendaftaran, $jenisBerkas, $fileName, $filePath, $fileType);

                if (!$stmt->execute()) {
                    echo "Gagal menyimpan ke database: " . $stmt->error;
                }
            } else {
                echo "Gagal mengupload $jenisBerkas.";
            }
        }
    }

    echo "<script>
    alert('Semua berkas berhasil diunggah!');
    window.location.href = 'homepage.php';
    </script>";
}
?>
