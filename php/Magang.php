<?php
class Magang {
    private $conn;

    public function __construct($host, $user, $password, $dbname) {
        $this->conn = new mysqli($host, $user, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
    }

    public function buatLowongan($judulMagang, $namaPerusahaan, $lokasi, $requirements, $logoUrl) {
        $stmt = $this->conn->prepare(
            "INSERT INTO magang (judul_magang, nama_perusahaan, lokasi, requirements, logo_url) 
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("sssss", $judulMagang, $namaPerusahaan, $lokasi, $requirements, $logoUrl);
    
        if ($stmt->execute()) {
            return "Lowongan magang berhasil disimpan.";
        } else {
            return "Gagal menyimpan lowongan: " . $stmt->error;
        }
    }
    
    

    public function ambilLowongan() {
        $sql = "SELECT id_magang, judul_magang, nama_perusahaan, lokasi, requirements, logo_url FROM magang";
        $result = $this->conn->query($sql);
        $data = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function __destruct() {
        $this->conn->close();
    }
}

$host = 'localhost'; 
$user = 'root'; 
$password = ''; 
$dbname = 'brawigigs'; 

include "connect.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judulMagang = $_POST["job-title"];
    $namaPerusahaan = $_POST["company-name"];
    $lokasi = $_POST["location"];
    $requirements = $_POST["requirements"];

    $uploadDir = "../uploads/";
    $fileTmpPath = $_FILES['logo_url']['tmp_name'];
    $fileName = $_FILES['logo_url']['name'];
    $fileSize = $_FILES['logo_url']['size'];
    $fileType = $_FILES['logo_url']['type'];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    // Validasi file hanya PNG
    if ($fileType === 'image/png' && $fileSize <= 2 * 1024 * 1024) { // Maks 2MB
        $newFileName = uniqid() . '.' . $fileExtension;
        $destPath = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $logoUrl = $destPath; // Simpan path ke database

            $magang = new Magang($host, $user, $password, $dbname);
            $result = $magang->buatLowongan($judulMagang, $namaPerusahaan, $lokasi, $requirements, $logoUrl);
            echo $result;
        } else {
            echo "Gagal mengunggah logo.";
        }
    } else {
        echo "Logo harus berupa file PNG dengan ukuran maksimal 2MB.";
    }
}

?>
