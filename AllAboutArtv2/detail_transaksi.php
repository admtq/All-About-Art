<?php
include "db.php";
session_start();

if (isset($_GET['id_transaksi'])) {
    $id_transaksi = intval($_GET['id_transaksi']);

    $sql = "
        SELECT 
            transaksi.*, 
            post.name_post, 
            post.caption_post, 
            post.created_datetime, 
            post_media.media_file, 
            users_app.name_user, 
            users_app.id_users_app, 
            users_app.foto_user
        FROM transaksi
        JOIN post ON transaksi.id_post = post.id_post
        JOIN post_media ON post.id_post = post_media.id_post
        JOIN users_app ON post.created_by_user_id = users_app.id_users_app
        WHERE transaksi.id_transaksi = ?
    ";

    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id_transaksi);
    $stmt->execute();
    $result = $stmt->get_result();
    $detail = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
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

        .image-container {
        text-align: center;
        margin-bottom: 20px;
    }

    .image-container img {
        max-width: 100%;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
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

    <section class="order-details">
        <div class="order-container">
            <h2>Detail Transaksi</h2>
            <?php if ($detail): ?>
                <div class="image-container">
                    <?php
                    $media_file = $detail['media_file'];
                    $folder = '';

                    if (str_starts_with($media_file, 'fotografi_')) {
                        $folder = 'dbpost/dbfotografi/';
                    } elseif (str_starts_with($media_file, 'videografi_')) {
                        $folder = 'dbpost/dbvideo/';
                    } elseif (str_starts_with($media_file, 'animasi_')) {
                        $folder = 'dbpost/dbanimasi/';
                    } elseif (str_starts_with($media_file, 'vector_')) {
                        $folder = 'dbpost/dbvector/';
                    } else {
                        $folder = 'dbpost/unknown/';
                    }

                    if (str_starts_with($media_file, 'animasi_') || str_starts_with($media_file, 'videografi_')) {
                        echo "<video controls><source src='{$folder}{$media_file}' type='video/mp4'>Browser Anda tidak mendukung pemutar video.</video>";
                    } else {
                        echo "<img src='{$folder}{$media_file}' alt='Media Postingan'>";
                    }
                    ?>
                </div>

                <table>
                    <tr>
                        <th>Nama Postingan</th>
                        <td><?php echo htmlspecialchars($detail['name_post']); ?></td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td><?php echo htmlspecialchars($detail['caption_post']); ?></td>
                    </tr>
                    <tr>
                        <th>Waktu Upload</th>
                        <td><?php echo htmlspecialchars($detail['created_datetime']); ?></td>
                    </tr>
                    <tr>
                        <th>Dibuat oleh</th>
                        <td>
                            <div class="profile-container">
                                <img src="<?php echo htmlspecialchars($detail['foto_user']); ?>" alt="Profile Picture">
                                <span><?php echo htmlspecialchars($detail['name_user']); ?></span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Jenis Transaksi</th>
                        <td><?php echo htmlspecialchars($detail['jenis_transaksi']); ?></td>
                    </tr>
                    <tr>
                        <th>Status Transaksi</th>
                        <td><?php echo htmlspecialchars($detail['status_transaksi']); ?></td>
                    </tr>
                    <tr>
                        <th>Total Bayar</th>
                        <td>Rp <?php echo number_format($detail['total_bayar'], 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <th>Waktu Transaksi</th>
                        <td><?php echo htmlspecialchars($detail['waktu_transaksi']); ?></td>
                    </tr>
                </table>

                <div class="button-container">
                    <button onclick="history.back()">Kembali</button>
                    <button onclick="location.href='profile_client.php?id_users_app=<?php echo $detail['id_users_app']; ?>'">Lihat Profil</button>
                </div>
            <?php else: ?>
                <p>Transaksi tidak ditemukan.</p>
            <?php endif; ?>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 All About Art. Semua Hak Dilindungi.</p>
    </footer>
</body>
</html>
