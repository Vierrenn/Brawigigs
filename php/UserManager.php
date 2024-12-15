<?php
include 'connect.php';
class UserManager {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function registerMahasiswa($namaLengkap, $programStudi, $nim, $password, $confirmPassword) {
        if ($password !== $confirmPassword) {
            echo "<script>
            alert('Password dan Konfirmasi tidak cocok!');
            window.location.href='../html/registerloginmahasiswa.html';
            </script>";
        }

        $password = md5($password); 

        $checkNIM = "SELECT * FROM mahasiswa WHERE nim = '$nim'";
        $result = $this->conn->query($checkNIM);

        if ($result->num_rows > 0) {
             echo "<script>
            alert('NIM sudah terdaftar!');
            window.location.href = '../html/registerloginmahasiswa.html';
            </script>";
        } else {
            $insertQuery = "INSERT INTO mahasiswa(nama_lengkap, program_studi, nim, password)
                            VALUES ('$namaLengkap', '$programStudi', '$nim', '$password')";

            if ($this->conn->query($insertQuery) === TRUE) {
                return "Berhasil";
            } else {
                return "Error saat menyimpan data: " . $this->conn->error;
            }
        }
    }

    public function registerPerusahaan($namaPerusahaan, $email, $password, $confirmPassword) {
        if ($password !== $confirmPassword) {
            echo "<script>
            alert('Password dan konfirmasi password tidak cocok!');
            window.location.href = '../html/registerloginperusahaan.html';
            </script>";
            exit();
        }

        $password = md5($password); 

        $checkEmail = "SELECT * FROM perusahaan WHERE email = '$email'";
        $result = $this->conn->query($checkEmail);

        if ($result->num_rows > 0) {
            echo "<script>
            alert('Email sudah terdaftar!');
            window.location.href='../html/registerloginperusahaan.html';
            </script>";
        } else {
            $insertQuery = "INSERT INTO perusahaan(nama_perusahaan, email, password)
                            VALUES ('$namaPerusahaan', '$email', '$password')";

            if ($this->conn->query($insertQuery) === TRUE) {
                return "Berhasil";
            } else {
                return "Error saat menyimpan data: " . $this->conn->error;
            }
        }
    }

    public function loginMahasiswa($nim, $password) {
        $password = md5($password); 

        $checkLogin = "SELECT * FROM mahasiswa WHERE nim = '$nim' AND password = '$password'";
        $result = $this->conn->query($checkLogin);

        if ($result->num_rows > 0) {
            session_start();
            $_SESSION['nim'] = $nim;
            header("Location: ../html/homepagemahasiswa.php");
            exit();
        } else {
            echo "<script>
            alert('NIM atau password salah!');
            window.location.href = '../html/registerloginmahasiswa.html';
          </script>";
         }
    }

    public function loginPerusahaan($email, $password) {
        $password = md5($password); 

        $checkLogin = "SELECT * FROM perusahaan WHERE email = '$email' AND password = '$password'";
        $result = $this->conn->query($checkLogin);

        if ($result->num_rows > 0) {
            session_start();
            $_SESSION['email'] = $email;
            header("Location: ../html/homepageperusahaan.php");
            exit();
        } else {
            echo "<script>
            alert('Email atau password salah!');
            window.location.href='../html/registerloginperusahaan.html';
            </script>"; 
        }
    }
}


$userManager = new UserManager($conn);
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
        echo $response;
    }
}

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
        echo $response;
    }
}

if (isset($_POST['login-button-mahasiswa'])) {
    echo $userManager->loginMahasiswa($_POST['login-nim-mahasiswa'], $_POST['login-password-mahasiswa']);
}

if (isset($_POST['login-button-perusahaan'])) {
    echo $userManager->loginPerusahaan($_POST['login-email-perusahaan'], $_POST['login-password-perusahaan']);
}

?>

