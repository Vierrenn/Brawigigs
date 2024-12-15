<?php
include "../php/connect.php";

class Pengumuman {
    private $conn;

    // Konstruktor untuk inisialisasi koneksi
    public function __construct($host, $user, $password, $dbname) {
        $this->conn = new mysqli($host, $user, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
    }

    // Method untuk menyimpan pengumuman
    public function simpanPengumuman($id_pendaftaran, $status, $kalimat_pengumuman) {
        // Menyiapkan query untuk menyimpan pengumuman
        $stmt = $this->conn->prepare("INSERT INTO pengumuman (id_pendaftaran, status, kalimat_pengumuman) 
                                      VALUES (?, ?, ?) 
                                      ON DUPLICATE KEY UPDATE status = VALUES(status), kalimat_pengumuman = VALUES(kalimat_pengumuman)");
        $stmt->bind_param("iss", $id_pendaftaran, $status, $kalimat_pengumuman);
        
        if ($stmt->execute()) {
            return "Pengumuman berhasil disimpan.";
        } else {
            return "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    public function umumkanSeleksi() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_pendaftaran = $_POST['id_pendaftaran'];
            $status = $_POST['status'];
            $kalimat_pengumuman = $_POST['kalimat_pengumuman'];

            $result = $this->simpanPengumuman($id_pendaftaran, $status, $kalimat_pengumuman);
            echo $result;
        }
    }

    public function __destruct() {
        $this->conn->close();
    }
}

$pengumuman = new Pengumuman('localhost', 'root', '', 'brawigigs');
$pengumuman->umumkanSeleksi();
?>
