<?php
session_start();

// Pastikan session mahasiswa sudah ada
if (!isset($_SESSION['nim'])) {
    exit();
}

include "../php/connect.php";

// Ambil NIM mahasiswa dari session
$nim = $_SESSION['nim'];

// Periksa apakah mahasiswa sudah memiliki id_pendaftaran
$sql_check = "SELECT id_pendaftaran FROM pendaftaran WHERE nim_mahasiswa = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("s", $nim);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Jika sudah ada, ambil id_pendaftaran-nya
    $row = $result->fetch_assoc();
    $id_pendaftaran = $row['id_pendaftaran'];
} else {
    // Jika belum ada, buat pendaftaran baru
    $sql_insert = "INSERT INTO pendaftaran (nim_mahasiswa, email_perusahaan) VALUES (?, 'google@gmail.com')";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("s", $nim);

    if ($stmt->execute()) {
        $id_pendaftaran = $stmt->insert_id;
    } else {
        die("Gagal membuat pendaftaran baru: " . $stmt->error);
    }
}

// Simpan id_pendaftaran ke session agar bisa digunakan di tempat lain
$_SESSION['id_pendaftaran'] = $id_pendaftaran;
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detail Magang - Unggah Berkas</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome-6.4.2/css/all.min.css" />
  <link rel="stylesheet" href="../css/daftarmagang.css" />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap"
    rel="stylesheet"
  />
</head>
<body>
  <header>
    <div id="navbar" class="obj-width">
      <a href="homepage.php"><img class="logo" src="../asset/logo.png" alt="Logo" /></a>
      <ul id="menu">
        <li><a href="homepage.php">Home</a></li>
        <li><a href="homepage.php#hero-section">Cari Magang</a></li>
        <li><a href="#">Lihat Pengumuman</a></li>
        <li><a href="homepage.php">Tentang Kami</a></li>
        <button id="login-navbar-btn">Logout</button>
      </ul>
    </div>
  </header>

  <div class="body-background">
    <div class="extra-space-div obj-width">
      <h1>Unggah Berkas Pendaftaran Magang</h1>
      <p>Unggah semua berkas yang diperlukan untuk pendaftaran magang Anda. Pastikan file dalam format yang benar dan tidak melebihi ukuran maksimal.</p>
    </div>

    <form class="extra-space-form obj-width" id="upload-form" action="../php/uploadberkas.php" method="post" enctype="multipart/form-data">
      <!-- ID Pendaftaran -->
      <input type="hidden" name="id_pendaftaran" value="<?php echo $id_pendaftaran; ?>">

      <div class="form-group">
        <label for="cv">Curriculum Vitae (CV):</label>
        <input type="file" id="cv" name="berkas[CV]" accept=".pdf" required />
        <p>Menerima file: .pdf</p>
      </div>

      <div class="form-group">
        <label for="transkrip">Transkrip Nilai:</label>
        <input type="file" id="transkrip" name="berkas[Transkrip]" accept=".pdf" />
        <p>Menerima file: .pdf</p>
      </div>

      <div class="form-group">
        <label for="rekomendasi">Surat Rekomendasi:</label>
        <input type="file" id="rekomendasi" name="berkas[Surat Rekomendasi]" accept=".pdf" required />
        <p>Menerima file: .pdf</p>
      </div>

      <div class="form-group">
        <label for="sptjm">Surat Pernyataan Tanggungjawab Mutlak (SPTJM):</label>
        <input type="file" id="sptjm" name="berkas[SPTJM]" accept=".pdf" required />
        <p>Menerima file: .pdf</p>
      </div>

      <div class="form-group">
        <label for="ktp">Foto/Scan KTP:</label>
        <input type="file" id="ktp" name="berkas[KTP]" accept=".jpg, .jpeg, .png, .pdf" required />
        <p>Menerima file: .jpg, .jpeg, .png, .pdf</p>
      </div>

      <button type="submit">Submit</button>
      <button type="reset">Reset</button>
    </form>
  </div>
</body>
</html>
