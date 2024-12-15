<?php
session_start();

include 'connect.php';

class UserManager {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function registerMahasiswa($namaLengkap, $programStudi, $nim, $password, $confirmPassword) {
        if ($password !== $confirmPassword) {
            return "Password dan Konfirmasi tidak cocok!";
        }

        $password = md5($password); // Enkripsi password

        // Periksa apakah NIM sudah terdaftar
        $checkNIM = "SELECT * FROM mahasiswa WHERE nim = ?";
        $stmt = $this->conn->prepare($checkNIM);
        $stmt->bind_param("s", $nim);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return "NIM sudah terdaftar!";
        } else {
            // Masukkan data mahasiswa ke database
            $insertQuery = "INSERT INTO mahasiswa (nama_lengkap, program_studi, nim, password) 
                            VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($insertQuery);
            $stmt->bind_param("ssss", $namaLengkap, $programStudi, $nim, $password);
            if ($stmt->execute()) {
                return "Berhasil";
            } else {
                return "Error saat menyimpan data: " . $this->conn->error;
            }
        }
    }

    public function registerPerusahaan($namaPerusahaan, $email, $password, $confirmPassword) {
        if ($password !== $confirmPassword) {
            return "Password dan konfirmasi password tidak cocok!";
        }

        $password = md5($password); // Enkripsi password

        // Periksa apakah email sudah terdaftar
        $checkEmail = "SELECT * FROM perusahaan WHERE email = ?";
        $stmt = $this->conn->prepare($checkEmail);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return "Email sudah terdaftar!";
        } else {
            // Masukkan data perusahaan ke database
            $insertQuery = "INSERT INTO perusahaan (nama_perusahaan, email, password) 
                            VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($insertQuery);
            $stmt->bind_param("sss", $namaPerusahaan, $email, $password);
            if ($stmt->execute()) {
                return "Berhasil";
            } else {
                return "Error saat menyimpan data: " . $this->conn->error;
            }
        }
    }

    public function loginMahasiswa($nim, $password) {
        $password = md5($password); // Enkripsi password

        // Periksa login mahasiswa
        $checkLogin = "SELECT * FROM mahasiswa WHERE nim = ? AND password = ?";
        $stmt = $this->conn->prepare($checkLogin);
        $stmt->bind_param("ss", $nim, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            session_start();
            $row = $result->fetch_assoc();
            $_SESSION['nim'] = $row['nim'];
            $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
            header("Location: ../html/homepagemahasiswa.php");
            exit();
        } else {
            return "NIM atau password salah!";
        }
    }

    public function loginPerusahaan($email, $password) {
        $password = md5($password); // Enkripsi password

        // Periksa login perusahaan
        $checkLogin = "SELECT * FROM perusahaan WHERE email = ? AND password = ?";
        $stmt = $this->conn->prepare($checkLogin);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            session_start();
            $row = $result->fetch_assoc();
            $_SESSION['email'] = $row['email'];
            $_SESSION['nama_perusahaan'] = $row['nama_perusahaan'];
            header("Location: ../html/homepageperusahaan.php");
            exit();
        } else {
            return "Email atau password salah!";
        }
    }
}

$userManager = new UserManager($conn);

// Proses registrasi mahasiswa
if (isset($_POST['register-button-mahasiswa'])) {
    $response = $userManager->registerMahasiswa(
        $_POST['namaLengkap'], 
        $_POST['programStudi'], 
        $_POST['nim'], 
        $_POST['password'], 
        $_POST['confirmPassword']
    );

    if ($response === "Berhasil") {
        echo "<script>
        alert('Registrasi berhasil!');
        window.location.href = '../html/registerloginmahasiswa.html'; 
        </script>";
    } else {
        echo "<script>
        alert('$response');
        window.location.href = '../html/registerloginmahasiswa.html'; 
        </script>";
    }
}

// Proses registrasi perusahaan
if (isset($_POST['register-button-perusahaan'])) {
    $response = $userManager->registerPerusahaan(
        $_POST['namaPerusahaan'], 
        $_POST['email'], 
        $_POST['password-perusahaan'], 
        $_POST['confirmPassword-perusahaan']
    );

    if ($response === "Berhasil") {
        echo "<script>
        alert('Registrasi berhasil!');
        window.location.href = '../html/registerloginperusahaan.html'; 
        </script>";
    } else {
        echo "<script>
        alert('$response');
        window.location.href = '../html/registerloginperusahaan.html'; 
        </script>";
    }
}

// Proses login mahasiswa
if (isset($_POST['login-button-mahasiswa'])) {
    $response = $userManager->loginMahasiswa(
        $_POST['login-nim-mahasiswa'], 
        $_POST['login-password-mahasiswa']
    );

    if ($response) {
        echo "<script>
        alert('$response');
        window.location.href = '../html/registerloginmahasiswa.html'; 
        </script>";
    }
}

// Proses login perusahaan
if (isset($_POST['login-button-perusahaan'])) {
    $response = $userManager->loginPerusahaan(
        $_POST['login-email-perusahaan'], 
        $_POST['login-password-perusahaan']
    );

    if ($response) {
        echo "<script>
        alert('$response');
        window.location.href = '../html/registerloginperusahaan.html'; 
        </script>";
    }
}
?>
