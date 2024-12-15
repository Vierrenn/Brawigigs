<?php
session_start(); // Memulai sesi untuk mengakses data sesi

// Cek apakah perusahaan sudah login
if (!isset($_SESSION['email'])) {
    // Jika tidak ada sesi login perusahaan, arahkan ke halaman login perusahaan
    header("Location: ../html/registerloginperusahaan.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="'en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Homepage BrawiGigs</title>
    <link rel="stylesheet" href="../css/homepage.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
      rel="stylesheet"
    />

    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">
  </head>


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

  <!-- Hero Section -->
  <section class="hero" id="hero">
    <div class="hero-box obj-width">
        <div class="h-left">
            <h1>Buat lowongan magang untuk perusahaan Anda</h1>
            <p>Anda juga dapat melakukan dan mengumumkan seleksi pendaftar magang ke perusahaan Anda!</p>
        
          </div>

            <div class="h-right">
                <img src="../asset/herosection2.png"
                alt="" >
            </div>
        </div>
    </div>
</section>


  
 </section>

  <script src="js/toggle.js"></script>

  <script src="js/Mahasiswa.js"></script>

  <script>
    document
      .getElementById("login-navbar-btn")
      .addEventListener("click", function () {
        window.location.href = "index.html";
      });
  </script>

  <script>
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault();
    document.querySelector(this.getAttribute("href")).scrollIntoView({
      behavior: "smooth",
      block: "start",
    });
  });
});
  </script>
</html>
