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

    public function buatPendaftaran($idMahasiswa, $idMagang) {
        $stmt = $this->conn->prepare(
            "INSERT INTO pendaftaran (id_mahasiswa, id_magang, status_pendaftaran) VALUES (?, ?, 'Pending')"
        );
        $stmt->bind_param("ii", $idMahasiswa, $idMagang);

        if ($stmt->execute()) {
            return $this->conn->insert_id; // Kembalikan id_pendaftaran yang baru dibuat
        } else {
            return false;
        }
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

session_start();
$idMahasiswa = $_SESSION['nim']; 

$magang = new Magang($host, $user, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_magang'])) {
    $idMagang = $_POST['id_magang'];

    // Buat pendaftaran baru
    $idPendaftaran = $magang->buatPendaftaran($idMahasiswa, $idMagang);

    if ($idPendaftaran) {
        $_SESSION['id_pendaftaran'] = $idPendaftaran; // Simpan id_pendaftaran ke sesi
        header("Location: daftarmagang.php"); // Redirect ke halaman unggah berkas
        exit();
    } else {
        echo "Gagal membuat pendaftaran.";
    }
}

$lowongan = $magang->ambilLowongan();
?>


