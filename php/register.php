<?php 

include 'connect.php';

if (isset($_POST['register-button'])) {
    $namaLengkap = $_POST['namaLengkap'];
    $programStudi = $_POST['programStudi'];
    $nim = $_POST['nim'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validasi konfirmasi password
    if ($password !== $confirmPassword) {
        echo "Password dan konfirmasi password tidak cocok!";
        exit();
    }

    // Enkripsi password
    $password = md5($password);

    // Cek apakah NIM sudah terdaftar
    $checkNIM = "SELECT * FROM mahasiswa WHERE nim = '$nim'";
    $result = $conn->query($checkNIM);

    if ($result->num_rows > 0) {
        echo "NIM sudah terdaftar!";
    } else {
        $insertQuery = "INSERT INTO mahasiswa(nama_lengkap, program_studi, nim, password)
                        VALUES ('$namaLengkap', '$programStudi', '$nim', '$password')";

        if ($conn->query($insertQuery) === TRUE) {
            echo("Registrasi berhasil");
            header("Location: index.html");
            exit();
        } else {
            echo "Error saat menyimpan data: " . $conn->error;
        }
    }
}
?>
