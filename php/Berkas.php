<?php
session_start();

class Berkas {
    private $conn;
    private $id_pendaftaran;
    private $upload_dir = "berkas/";
    private $allowed_ext = ['pdf', 'jpg', 'jpeg', 'png'];

    // Konstruktor untuk inisialisasi koneksi dan ID pendaftaran
    public function __construct($host, $user, $password, $dbname) {
        $this->id_pendaftaran = $_SESSION['id_pendaftaran'] ?? null;
        
        if (!$this->id_pendaftaran) {
            die("ID Pendaftaran tidak ditemukan. Silakan daftar terlebih dahulu.");
        }

        // Koneksi ke database
        $this->conn = new mysqli($host, $user, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
    }

    private function validasiEkstensiFile($file_ext) {
        return in_array(strtolower($file_ext), $this->allowed_ext);
    }

    private function unggahFile($tmp_name, $nama_file) {
        $file_ext = pathinfo($nama_file, PATHINFO_EXTENSION);
        $nama_file_uniq = uniqid() . "_" . $nama_file;
        $path_file = $this->upload_dir . $nama_file_uniq;

        if ($this->validasiEkstensiFile($file_ext)) {
            if (move_uploaded_file($tmp_name, $path_file)) {
                return [$path_file, $file_ext];
            } else {
                return false;
            }
        }

        return false;
    }

    private function simpanFileKeDatabase($jenis_berkas, $nama_file, $path_file, $file_ext) {
        $stmt = $this->conn->prepare(
            "INSERT INTO berkas (id_pendaftaran, jenis_berkas, nama_file, path_file, tipe_file) 
            VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("issss", $this->id_pendaftaran, $jenis_berkas, $nama_file, $path_file, $file_ext);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function unggahSemuaBerkas() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['berkas'])) {
            foreach ($_FILES['berkas']['name'] as $jenis_berkas => $nama_file) {
                if (!empty($nama_file)) {
                    $tmp_name = $_FILES['berkas']['tmp_name'][$jenis_berkas];

                    // Pindahkan file dan simpan metadata
                    list($path_file, $file_ext) = $this->unggahFile($tmp_name, $nama_file);

                    if ($path_file) {
                        if ($this->simpanFileKeDatabase($jenis_berkas, $nama_file, $path_file, $file_ext)) {
                            echo "File {$nama_file} berhasil diunggah.<br>";
                        } else {
                            echo "Gagal menyimpan file {$nama_file} ke database.<br>";
                        }
                    } else {
                        echo "Gagal mengunggah file {$nama_file}.<br>";
                    }
                }
            }
        } else {
            echo "Tidak ada file yang diunggah.<br>";
        }
    }

    // Menutup koneksi database
    public function __destruct() {
        $this->conn->close();
    }
}

$berkas = new Berkas('localhost', 'root', '', 'brawigigs');
$berkas->unggahSemuaBerkas();
?>
