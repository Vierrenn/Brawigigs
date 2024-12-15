<?php
include '../php/connect.php'; 

$idMagang = $_GET['id_magang']; 

$query = "SELECT id_magang, judul_magang, nama_perusahaan, lokasi, requirements, logo_url 
          FROM magang WHERE id_magang = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $idMagang); 
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc()
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detail Magang - Google</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome-6.4.2/css/all.min.css" />
  <link rel="stylesheet" href="../css/homepage.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
      rel="stylesheet"
    />
</head>

<!-- Navbar -->
<body>
  <header>
    <div id="navbar" class="obj-width">
      <a href="homepage.html"
        ><img class="logo" id="logo" src="../asset/logo.png" alt=""
      /></a>
      <ul id="menu">
        <li><a href="#hero">Home</a></li>
        <li><a href="#search-bar">Cari Magang</a></li>
        <li><a href="">Lihat Pengumuman</a></li>
        <li><a href="#features">Tentang Kami</a></li>
        <button id="login-navbar-btn">Logout</button>
      </ul>

      <i id="bar" class='bx bx-menu'></i>
    </div>
  </header>
  <main id="detailMagang" class="extra-space obj-width">
  <div class="internship-header">
    <div class="internship-image-row">
      <!-- Gambar logo perusahaan -->
      <img src="<?php echo htmlspecialchars($data['logo_url']); ?>" alt="Logo <?php echo htmlspecialchars($data['nama_perusahaan']); ?>" />
      <div>
        <!-- Nama perusahaan -->
        <h2><?php echo htmlspecialchars($data['nama_perusahaan']); ?></h2>
      </div>
    </div>
    <a id="daftar-btn" href="daftarmagang.php">Daftar Sekarang</a>
  </div>
  

  <body>
    <div class="deskripsi-magang extra-space obj-width">
      <div>
        <!-- Bidang Magang -->
        <img src="../asset/position.png" alt="">
        <h3>Bidang Magang</h3>
        <p><?php echo htmlspecialchars($data['judul_magang']); ?></p>
      </div>

      <div>
        <!-- Lokasi -->
        <img src="../asset/location.png" alt="">
        <h3>Lokasi</h3>
        <p><?php echo htmlspecialchars($data['lokasi']); ?></p>
      </div>

      <div>
        <!-- Requirements -->
        <img src="../asset/skill.png" alt="">
        <h3>Requirements</h3>
        <p><?php echo htmlspecialchars($data['requirements']); ?></p>
      </div>
    </div>
  </body>
</main>


<script src="js/Magang.js"></script>
<script src="js/toggle.js"></script>
<script src="js/Mahasiswa.js"></script>


</body>
</html>
