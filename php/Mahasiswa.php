<?php
include 'connect.php';
session_start();

class Mahasiswa {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function uploadBerkas($id_pendaftaran, $berkas) {
        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
        $uploadDir = "../berkas/$id_pendaftaran/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        foreach ($berkas['name'] as $jenisBerkas => $fileName) {
            if (!empty($fileName)) {
                $fileTmp = $berkas['tmp_name'][$jenisBerkas];
                $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (!in_array($fileType, $allowedExtensions)) {
                    echo "<script>alert('Tipe file tidak valid untuk $jenisBerkas!');</script>";
                    continue;
                }

                $filePath = $uploadDir . $jenisBerkas . '.' . $fileType;

                if (move_uploaded_file($fileTmp, $filePath)) {
                    $query = "INSERT INTO berkas (id_pendaftaran, jenis_berkas, nama_file, path_file, tipe_file) 
                              VALUES (?, ?, ?, ?, ?)";
                    $stmt = $this->conn->prepare($query);
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

    public function logout() {
        $_SESSION = [];
        session_destroy();

        header("Location: login.php");
        exit();
    }
}

$mahasiswa = new Mahasiswa($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pendaftaran = $_POST['id_pendaftaran'];
    $berkas = $_FILES['berkas'];

    $mahasiswa->uploadBerkas($id_pendaftaran, $berkas);
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $mahasiswa->logout();
}
?>
