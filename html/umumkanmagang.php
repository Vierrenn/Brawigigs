<?php include '../php/connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kelola Berkas</title>
  <link rel="stylesheet" href="../css/umumkanmagang.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">
</head>
<body>
  <header>
    <div id="navbar" class="obj-width">
      <a href="homepageperusahaan.html">
        <img class="logo" id="logo" src="../asset/logo.png" alt="" />
      </a>
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
    <h1>Umumkan Hasil Magang</h1>
    <p>Berikut adalah daftar pendaftar magang yang perlu diumumkan.</p>

    <table id="pendaftar-table">
      <thead>
        <tr>
          <th>Nama Mahasiswa</th>
          <th>Program Studi</th>
          <th>Umumkan</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        // Query untuk mengambil data pendaftar beserta nama mahasiswa dan program studi
        $sql = "SELECT p.id_pendaftaran, m.nama_lengkap, m.program_studi 
                FROM pendaftaran p 
                JOIN mahasiswa m ON p.nim_mahasiswa = m.nim";  // Perhatikan hubungan yang tepat antara pendaftaran dan mahasiswa
        $result = $conn->query($sql);
        
        // Menampilkan data pendaftar
        while ($row = $result->fetch_assoc()) { 
        ?>
        <tr id="pendaftar-<?= $row['id_pendaftaran']; ?>">
          <td><?= $row['nama_lengkap']; ?></td>
          <td><?= isset($row['program_studi']) ? $row['program_studi'] : 'Tidak ada program studi'; ?></td>
          <td>
            <form action="../php/Perusahaan.php" method="POST" class="announcement-form">
              <input type="hidden" name="id_pendaftaran" value="<?= $row['id_pendaftaran']; ?>">
              <textarea name="kalimat_pengumuman" placeholder="Masukkan kalimat pengumuman..."></textarea>
              <br>
              <button type="submit" name="status" value="Lolos" class="success" onclick="removeRow(<?= $row['id_pendaftaran']; ?>)">Lolos</button>
              <button type="submit" name="status" value="Tidak Lolos" class="fail" onclick="removeRow(<?= $row['id_pendaftaran']; ?>)">Tidak Lolos</button>
            </form>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

  <?php $conn->close(); ?>

  <script>
    function removeRow(id) {
      const row = document.getElementById('pendaftar-' + id);
      if (row) {
        row.style.display = 'none';  // Hides the row from the table
      }
    }
  </script>
</body>
</html>
