<?php
// Include koneksi database
include '../php/connect.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman Seleksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        h2 {
            color: #333;
        }
        .announcement-box {
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 5px;
            background-color: #f9f9f9;
            margin-top: 20px;
        }
        .status {
            font-weight: bold;
        }
        .not-found {
            color: red;
        }
    </style>
</head>
<body>
    <h2>Pengumuman Seleksi</h2>

    <?php
    if (isset($_GET['id_pendaftaran'])) {
        $id_pendaftaran = $_GET['id_pendaftaran'];

        // Menyiapkan query untuk menampilkan pengumuman berdasarkan id_pendaftaran
        $stmt = $conn->prepare("SELECT status, kalimat_pengumuman, waktu_pengumuman 
                                FROM pengumuman WHERE id_pendaftaran = ?");
        $stmt->bind_param("i", $id_pendaftaran);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if ($data) {
            // Menampilkan pengumuman
            echo "<div class='announcement-box'>";
            echo "<p><span class='status'>Status: </span>" . $data['status'] . "</p>";
            echo "<p><strong>Pengumuman:</strong> " . $data['kalimat_pengumuman'] . "</p>";
            echo "<p><strong>Waktu Pengumuman:</strong> " . $data['waktu_pengumuman'] . "</p>";
            echo "</div>";
        } else {
            echo "<p class='not-found'>Belum ada pengumuman untuk ID Pendaftaran: " . $id_pendaftaran . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p class='not-found'>ID Pendaftaran tidak ditemukan. Harap masukkan ID Pendaftaran yang valid.</p>";
    }

    $conn->close();
    ?>

</body>
</html>
