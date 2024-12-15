<?php

require_once '../php/Magang.php'; 

$host = "localhost";
$user = "root";
$password = "";
$dbname = "brawigigs";

// Membuat objek dari kelas Magang
$magang = new Magang($host, $user, $password, $dbname);

// Mengambil data lowongan magang
$dataLowongan = $magang->ambilLowongan();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Homepage BrawiGigs</title>
    <link rel="stylesheet" href="../css/homepage.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/boxicons@2.1.2/css/boxicons.min.css"
      rel="stylesheet"
    />
  </head>

  <body>
    <!-- Header Section -->
    <header>
      <div id="navbar" class="obj-width">
        <a href="homepagemahasiswa.php">
          <img class="logo" id="logo" src="../asset/logo.png" alt="BrawiGigs" />
        </a>
        <ul id="menu">
          <li><a href="#hero">Home</a></li>
          <li><a href="#search-bar">Cari Magang</a></li>
          <li><a href="pengumuman.php">Lihat Pengumuman</a></li>
          <li><a href="#features">Tentang Kami</a></li>
          <button id="login-navbar-btn">Logout</button>
        </ul>
        <i id="bar" class="bx bx-menu"></i>
      </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="hero">
      <div class="hero-box obj-width">
        <div class="h-left">
          <h1>Cari magang yang relevan dengan bidang Anda</h1>
          <p>Daftarkan juga diri Anda ke magang yang Anda inginkan!</p>
        </div>
        <div class="h-right">
          <img src="../asset/herosection2.png" alt="Hero Section" />
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features sec-space obj-width">
      <h2>Fitur dari BrawiGigs</h2>
      <p>Anda bisa memanfaatkan fitur-fitur ini</p>
      <div class="feature-box">
        <div>
          <img src="../asset/feature-1.png" alt="" />
          <h3>Cari Magang</h3>
          <p>
            Anda dapat mencari magang yang relevan dengan bidang Anda menggunakan
            filter pencarian.
          </p>
        </div>
        <div>
          <img src="../asset/feature-2.png" alt="" />
          <h3>Daftar Magang</h3>
          <p>
            Anda dapat mendaftarkan diri ke magang yang Anda inginkan dengan
            mengumpulkan berkas yang dibutuhkan.
          </p>
        </div>
        <div>
          <img src="../asset/feature-3.png" alt="" />
          <h3>Lihat Pengumuman</h3>
          <p>
            Anda dapat melihat pengumuman seleksi magang dari perusahaan yang
            Anda daftarkan.
          </p>
        </div>
        <div>
          <img src="../asset/feature-4.png" alt="" />
          <h3>Semua Fitur Gratis</h3>
          <p>Anda dapat menggunakan semua fitur di website ini secara gratis.</p>
        </div>
      </div>
    </section>

    <!-- Job Listing Section -->
    <section class="internship sec-space obj-width">
      <h2>Magang Tersedia</h2>
      <p>Berikut ini adalah magang yang tersedia</p>

      <!-- Search Bar -->
      <form id="search-bar">
        <i class="bx bx-search-alt-2"></i>
        <input
          type="text"
          placeholder="Cari Magang"
          id="searchBar"
          autocomplete="off"
        />
      </form>

      <!-- Job Listing -->
      <div class="internship-container grid-layout">
        <ul style="list-style-type: none; padding: 0;">
          <?php if (!empty($dataLowongan)): ?>
            <?php foreach ($dataLowongan as $lowongan): ?>
              <li class="jList">
                <a href="magang.php?id_magang=<?php echo $lowongan['id_magang']; ?>" style="text-decoration: none; color: inherit;">
                  <img src="<?php echo htmlspecialchars($lowongan['logo_url']); ?>" alt="Logo" style="width:50px;height:50px;">
                  <h3><?php echo htmlspecialchars($lowongan['nama_perusahaan']); ?></h3>
                  <p><?php echo htmlspecialchars($lowongan['judul_magang']); ?></p>
                  <span id="location"><?php echo htmlspecialchars($lowongan['lokasi']); ?></span>
                </a>
              </li>
            <?php endforeach; ?>
          <?php else: ?>
            <p>Tidak ada lowongan tersedia saat ini.</p>
          <?php endif; ?>
        </ul>
      </div>
    </section>

    <!-- Scripts -->
    <script src="../js/toggle.js"></script>
    <script>
      // Logout Button
      document
        .getElementById("login-navbar-btn")
        .addEventListener("click", function () {
          window.location.href = "../index.html";
        });

      // Smooth Scrolling
      document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener("click", function (e) {
          e.preventDefault();
          document
            .querySelector(this.getAttribute("href"))
            .scrollIntoView({ behavior: "smooth", block: "start" });
        });
      });

      // Search Filter
      document.getElementById("searchBar").addEventListener("keyup", function () {
        let filter = this.value.toLowerCase();
        let items = document.querySelectorAll(".jList");

        items.forEach((item) => {
          let title = item.querySelector("h3").textContent.toLowerCase();
          let description = item.querySelector("p").textContent.toLowerCase();
          if (title.includes(filter) || description.includes(filter)) {
            item.style.display = "";
          } else {
            item.style.display = "none";
          }
        });
      });
    </script>
  </body>
</html>
