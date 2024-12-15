<?php
session_start();
include '../php/connect.php';

// Pastikan perusahaan sudah login
if (!isset($_SESSION['email'])) {
    header("Location: loginperusahaan.php"); // Arahkan ke halaman login jika belum login
    exit();
}

// Ambil email perusahaan dari sesi
$email_perusahaan = $_SESSION['email']; // Pastikan menggunakan 'email_perusahaan' dari session

$sql = "SELECT p.id_pendaftaran, b.jenis_berkas, b.nama_file, b.path_file, m.nama_lengkap 
        FROM pendaftaran p
        JOIN berkas b ON p.id_pendaftaran = b.id_pendaftaran
        JOIN mahasiswa m ON p.nim_mahasiswa = m.nim  
        WHERE p.email_perusahaan = ?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email_perusahaan);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Berkas</title>
    <link rel="stylesheet" href="../css/kelolamagang.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
      rel="stylesheet">
</head>
<body>
<header>
    <div id="navbar" class="obj-width">
      <a href="homepageperusahaan.html"
        ><img class="logo" id="logo" src="../asset/logo.png" alt=""
      /></a>
      <ul id="menu">
      <li><a href="homepageperusahaan.php">Home</a></li>
          <li><a href="buatmagang.html">Buat Lowongan</a></li>
          <li><a href="lihatberkas.php">Lihat Berkas</a></li>
          <li><a href="umumkanmagang.php">Umumkan Magang</a></li>
          <button id="login-navbar-btn" href="../index.html">Logout</button>
        </ul>
      <i id="bar" class='bx bx-menu'></i>
    </div>
    
  </header>

  <div class="container">
    <h1>Daftar Berkas Pendaftaran Magang</h1>
    <p>Berikut adalah berkas-berkas yang telah diunggah oleh mahasiswa:</p>

    <?php if ($result->num_rows > 0): ?>
        <table class="file-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pendaftar</th>
                    <th>Jenis Berkas</th>
                    <th>Nama File</th>
                    <th>Berkas</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1; 
                while ($row = $result->fetch_assoc()): 
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                        <td><?php echo htmlspecialchars($row['jenis_berkas']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_file']); ?></td>
                        <td>
                            <a href="<?php echo htmlspecialchars($row['path_file']); ?>" target="_blank" class="view-btn">Lihat Berkas</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Belum ada berkas yang diunggah untuk perusahaan ini.</p>
    <?php endif; ?>
</div>

  </div>
</body>
</html>
