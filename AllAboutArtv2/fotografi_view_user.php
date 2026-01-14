<?php
include "db.php";
session_start();

if (isset($_GET['id_post'])) {
    $id_post = intval($_GET['id_post']);
    $sql = "SELECT * FROM post_media WHERE id_post = $id_post";
    $sql_text = "SELECT * FROM post WHERE id_post = $id_post";
    $result = mysqli_query($db, $sql);
    $result_text = mysqli_query($db, $sql_text);
    $view = mysqli_fetch_assoc($result);
    $view_text = mysqli_fetch_assoc($result_text);
}

$id_user = $view_text['created_by_user_id'];
$result_user = mysqli_query($db, "SELECT * FROM users_app WHERE id_users_app = '$id_user'");
if ($result_user && mysqli_num_rows($result_user) > 0) {
    $data = mysqli_fetch_assoc($result_user);
}

// Hapus data
if (isset($_GET['delete'])) {
    $id_post = $_GET['delete'];
    $db->query("DELETE FROM post WHERE id_post=$id_post");
    header("Location: profile.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postingan Saya</title>
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
            <h2>Postingan Saya</h2>

            <!-- Gambar Postingan -->
            <?php if (!empty($view['media_file'])): ?>
                <div class="image-container">
                    <img src="dbpost/dbfotografi/<?php echo htmlspecialchars($view['media_file']); ?>" alt="Gambar Postingan" />
                </div>
            <?php else: ?>
                <p>Gambar tidak tersedia.</p>
            <?php endif; ?>

            <table>
                <tr>
                    <th>Judul Postingan</th>
                    <td><?php echo htmlspecialchars($view_text['name_post']); ?></td>
                </tr>
                <tr>
                    <th>Harga</th>
                    <td>Rp <?php echo number_format($view_text['harga_post'], 0, ',', '.'); ?></td>
                </tr>
                <th>Waktu Upload</th>
                    <td><?php echo htmlspecialchars($view_text['created_datetime']); ?></td>
                <tr>
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

            <div class="button-container">
                <button onclick="history.back()">Kembali</button>
                <!-- <button onclick="return confirm('Apakah ngana yakin?') location.href='?delete=<?= $view_text['id_post'] ?>'">Hapus Postingan</button> -->
                <a href="?delete=<?= $view_text['id_post'] ?>" class="edit-profile-btn" onclick="return confirm('Apakah ngana yakin?')">Hapus Postingan</a>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 All About Art. Semua Hak Dilindungi.</p>
    </footer>
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
        width: 48%;
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

    .edit-profile-btn {
            width: 48%;
            padding: 10px;
            background-color: #3C0753;
            color: white;
            font-size: 1rem;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .edit-profile-btn:hover {
            background-color: #910A67;
        }

    footer {
        background-color: #030637;
        color: white;
        text-align: center;
        padding: 20px;
        margin-top: 40px;
        bottom:0 ;
        position: absolute;
        width: 100%;
    }
</style>
</html>