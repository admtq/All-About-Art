<?php
include "db.php";
session_start();

// Cek apakah id_post ada di URL
if (isset($_GET['id_post'])) {
    $id_post = intval($_GET['id_post']);

    // Ambil data post_media
    $sql = "SELECT * FROM post_media WHERE id_post = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id_post);
    $stmt->execute();
    $result = $stmt->get_result();
    $view = $result->fetch_assoc();

    // Ambil data post
    $sql_text = "SELECT * FROM post WHERE id_post = ?";
    $stmt_text = $db->prepare($sql_text);
    $stmt_text->bind_param("i", $id_post);
    $stmt_text->execute();
    $result_text = $stmt_text->get_result();
    $view_text = $result_text->fetch_assoc();
}

// Ambil data pengguna yang membuat postingan
$id_user = $view_text['created_by_user_id'] ?? null;
if ($id_user) {
    $stmt_user = $db->prepare("SELECT * FROM users_app WHERE id_users_app = ?");
    $stmt_user->bind_param("i", $id_user);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    $data = $result_user->fetch_assoc();
}

// Ambil data pengguna saat ini dari session
$username = $_SESSION['username'] ?? null;
if ($username) {
    $stmt_session = $db->prepare("SELECT * FROM users_app WHERE username = ?");
    $stmt_session->bind_param("s", $username);
    $stmt_session->execute();
    $result_session = $stmt_session->get_result();
    $data_session = $result_session->fetch_assoc();
    $id_session = $data_session['id_users_app'];
}

// Hitung total harga checkout
$harga_checkout = $view_text['harga_post'] + $view_text['id_post'] + $id_session;

// Jika tombol submit ditekan
if (isset($_POST['submit'])) {
    $status_default = "Menunggu konfirmasi";
    $jenis_default = "Pembelian";

    // Query untuk menyimpan transaksi
    $sql_tr = "INSERT INTO transaksi (id_post, id_users_app, total_bayar, status_transaksi, jenis_transaksi) VALUES (?, ?, ?, ?, ?)";
    $stmt_tr = $db->prepare($sql_tr);
    $stmt_tr->bind_param("iiiss", $id_post, $id_session, $harga_checkout, $status_default, $jenis_default);

    if ($stmt_tr->execute()) {
        header("Location: daftar_transaksi.php");
        exit();
    } else {
        echo "Error: " . $stmt_tr->error;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Postingan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <nav>
            <h1><a href="home_after_login.php">All About Art</a></h1>
            <a href="fotografi_list.php">Fotografi</a>
            <a href="videografi_list.php">Videografi</a>
            <a href="vector_list.php">Vector</a>
            <a href="animasi_list.php">Animasi</a>
            <a href="profile.php">Profil</a>
        </nav>
    </header>

    <!-- Detail Pemesanan Section -->
    <section class="order-details">
        <div class="order-container">
            <h2>Detail Postingan</h2>

            <div id="popupPost" class="popup">
            <div class="popup-content">
                <span class="close" id="closePopupPost">&times;</span>
                <h2>085234363927</h2>
                <h3>A.N. Adam Mutaqien</h3>
                <p>ShopeePay</p>
                <p><br></p>
                <h2>Rp <?php echo number_format($harga_checkout, 0, ',', '.'); ?></h2>
                <p>*Nominal diatas termasuk biaya tambahan untuk kode unik</p>
                <!-- <a>Batal</a>
                <button onclick="history.back()">Batal</button>
                <a type="button" onclick="submit">Selesai</a>
                <button type="submit" name="submit">Selesai</button> -->
                <form action="animasi_view.php?id_post=<?php echo htmlspecialchars($id_post); ?>" method="POST">
    <button type="submit" name="submit">Selesai</button>
</form>

            </div>
        </div>

            <!-- Gambar Postingan -->
            <?php if (!empty($view['media_file'])): ?>
                <div class="image-container">
                    <video controls>
                        <source src="dbpost/dbanimasi/<?php echo $view['media_file']; ?>">
                        Your browser does not support the video tag.
                    </video>
                </div>
            <?php else: ?>
                <p>Video tidak tersedia.</p>
            <?php endif; ?>

            <table>
                <tr>
                    <th>Judul Postingan</th>
                    <td><?php echo htmlspecialchars($view_text['name_post']); ?></td>
                </tr>
                <tr>
                    <th>Deskripsi</th>
                    <td><?php echo htmlspecialchars($view_text['caption_post']); ?></td>
                </tr>
                <tr>
                    <th>Harga</th>
                    <td>Rp <?php echo number_format($view_text['harga_post'], 0, ',', '.'); ?></td>
                </tr>
                <th>Waktu Upload</th>
                    <td><?php echo htmlspecialchars($view_text['created_datetime']); ?></td>
                <tr>
                    <th>Dibuat oleh</th>
                    <td>
                        <div class="profile-container">
                            <img src="<?php echo htmlspecialchars($data['foto_user']); ?>" alt="Profile Picture">
                            <span><?php echo htmlspecialchars($data['name_user']); ?></span>
                        </div>
                    </td>
                </tr>
            </table>

            <h3><br></h3>
            <h3>Langkah-Langkah Melakukan Pembelian</h3>
            <p>1. Klik tombol 'Beli'</p>
            <p>2. Setelah itu akan muncul popup berisi nominal yang harus dibayarkan beserta rekening tujuan</p>
            <p>3. Pembayaran ditujukan pada rekening bersama</p>
            <p>4. Setelah melakukan pembayaran, klik tombol 'Selesai'</p>
            <p>5. Lama konfirmasi pembayaran sekitar 2-3 hari</p>
            <a href="https://wa.me/6285234363927">6. Apabila terdapat kendala, klik disini untuk menghubungi admin</a>
            <h3><br></h3>
            <div class="button-container">
                <button onclick="history.back()">Kembali</button>
                <button onclick="location.href='profile_client.php?id_users_app=<?php echo $data['id_users_app']; ?>'">Lihat Profil</button>
                <button id=openPopupPost>Beli</button>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 All About Art. Semua Hak Dilindungi.</p>
    </footer>
    <script src="popup.js"></script>
</body>

<style>
    /* Basic Styling */
    body {
        font-family: 'Montserrat', sans-serif;
        margin: 0;
        background-color: #f4f4f4;
    }

    header {
        background-color: #3C0753;
        padding: 15px 20px;
    }

    h1 {
        color: white;
        margin: 0;
    }

    nav {
        display: flex;
        justify-content: space-around;
        align-items: center;
    }

    nav a {
        text-decoration: none;
        color: white;
        font-weight: bold;
        padding: 5px 10px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    nav a:hover {
        background-color: #910A67;
    }

    /* Detail Pemesanan Section */
    .order-details {
        margin: 40px auto;
        max-width: 600px;
        background-color: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
    }

    .order-container h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .order-container a {
        text-decoration: none;
        color: black;
    }

    .image-container {
        text-align: center;
        margin-bottom: 20px;
    }

    .image-container img {
        max-width: 100%;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table th, table td {
        text-align: left;
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    table th {
        background-color: #f9f9f9;
        font-weight: bold;
    }

    table td {
        color: #555;
    }

    .profile-container {
    display: flex;
    align-items: center;
}

.profile-container img {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    margin-right: 10px;
}


    .button-container {
        display: flex;
        justify-content: space-between;
    }

    .button-container button {
        width: 32%;
        padding: 10px;
        background-color: #3C0753;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .button-container button:hover {
        background-color: #910A67;
    }

    .popup {
    display: none; /* Sembunyikan pop-up secara default */
    position: fixed;
    z-index: 1; /* Di atas konten lainnya */
    left: 0;
    top: 0;
    width: 100%; /* Lebar penuh */
    height: 100%; /* Tinggi penuh */
    background-color: rgba(0, 0, 0, 0.5); /* Latar belakang semi-transparan */
    }

    .popup-content {
        text-align: center;
        background-color: #fff;
        margin: 15% auto; /* Pusatkan pop-up */
        border: 1px solid #888;
        padding: 10px;
        width: fit-content; /* Lebar pop-up */
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    .popup-content h2 {
        margin-left: 50px;
        margin-right: 50px;
        text-align: center;
    }

    .popup-content p {
        text-align: center;
    }

    .popup-content button {
        text-align: center;
        color: white;
        background-color: #3C0753;
        padding: 10px 20px;
        border-radius: 5px;
        display: inline-block ;
        margin-top: 10px;
        margin-bottom: 20px;
        margin-left: 6px;
    }

    .popup-content button:hover {
        background-color: #002f3b;
    }

    .close {
        color: #aaa;
        float: right;
        margin-right: 15px;
        margin-top: 10px;
        font-size: 38px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    footer {
        background-color: #030637;
        color: white;
        text-align: center;
        padding: 20px;
        margin-top: 40px;
        bottom:0 ;
        width: 100%;
    }
</style>
</html>