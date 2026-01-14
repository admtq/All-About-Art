<?php
include "db.php";
session_start();
$username = $_SESSION['username'];
$id_post = 0;

// Retrieve the Name
$result = mysqli_query($db, "SELECT * FROM users_app WHERE username = '$username'");
if ($result && mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
    $user_description = $data['description_user']; // Get the user description
    $user_name = $data['name_user']; // Get the user Name
    $user_foto = $data['foto_user']; // Get the user foto
    $id_user = $data['id_users_app']; // get uswr id
} else {
    header("location: login.php");
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
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
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-pic">
                <img src="<?php echo $user_foto ?: 'profileusers/default.png' ?>" alt="Profile">
            </div>
            <div class="profile-info">
                <h2 class="username"><?php echo $user_name ?></h2>
                <div class="profile-bio">
                    <p>"<?php echo $user_description ?>"</p>
                    <!-- <p><a href="link.whatsapp">Request Content</a></p> -->
                </div>
                <a id="openPopupPost" class="edit-profile-btn">Tambah Postingan</a>
                <a href="profile_edit.php"><button class="edit-profile-btn">Edit Profil</button></a>
                <a href="logout.php"><button class="edit-profile-btn">Logout</button></a>
            </div>
        </div>

        <div class="profile-menu">
            <a href="profile.php" class="menu-item">Postingan</a>
            <a href="daftar_transaksi.php" class="menu-item">Daftar Transaksi</a>
        </div>

        <div id="popupPost" class="popup">
            <div class="popup-content">
                <span class="close" id="closePopupPost">&times;</span>
                <h2>Apa yang ingin anda posting?</h2>
                <p>Tentukan apa yang ingin anda tunjukkan kepada dunia.</p>
                <a href="upload_fotografi.php">Fotografi</a>
                <a href="upload_videografi.php">Videografi</a>
                <a href="upload_vector.php">Vector</a>
                <a href="upload_animasi.php">Animasi</a>
            </div>
        </div>

        <!-- Posting Section -->

        <div class="gallery" id="gallery">
            <?php

            // Query untuk mendapatkan media berdasarkan id_user dan id_post
            $query = "
                SELECT post_media.* 
                FROM post_media post_media
                INNER JOIN post post ON post_media.id_post = post.id_post
                WHERE post.created_by_user_id = '$id_user'
            ";

            $result_user = mysqli_query($db, $query);

            // Tampilkan hasilnya
            while ($row = mysqli_fetch_assoc($result_user)) {
            ?>
                <div class="gallery-item">
                    <!-- Periksa apakah media adalah gambar -->
                    <?php if (preg_match('/^fotografi_/i', $row['media_file'])) { ?>
                        <a href="fotografi_view_user.php?id_post=<?php echo $row['id_post']; ?>">
                            <img src="dbpost/dbfotografi/<?php echo $row['media_file']; ?>" alt="<?php echo $row['media_file']; ?>">
                        </a>

                    <!-- Periksa apakah media adalah video -->
                    <?php } if (preg_match('/^videografi_/i', $row['media_file'])) { ?>
                        <a href="videografi_view_user.php?id_post=<?php echo $row['id_post']; ?>">
                            <video controls class="video">
                                <source src="dbpost/dbvideografi/<?php echo $row['media_file']; ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </a>

                    <!-- Periksa apakah media adalah vektor -->
                    <?php } if (preg_match('/^vector_/i', $row['media_file'])) { ?>
                        <a href="vector_view_user.php?id_post=<?php echo $row['id_post']; ?>">
                            <img src="dbpost/dbvector/<?php echo $row['media_file']; ?>" alt="Vector File: <?php echo $row['media_file']; ?>">
                        </a>

                    <!-- Periksa apakah media adalah animasi -->
                    <?php } if (preg_match('/^animasi_/i', $row['media_file'])) { ?>
                        <a href="animasi_view_user.php?id_post=<?php echo $row['id_post']; ?>">
                            <video controls class="video">
                                <source src="dbpost/dbanimasi/<?php echo $row['media_file']; ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </a>
                    <?php } ?>
                </div>
            <?php
            }
            ?>
        </div>



        <!-- <div class="posts">
            <h2>Postingan Terakhir</h2>
            <div class="post-card">
                <div class="post-content">
                    <h3>Anda Belum Memposting apapun</h3>
                </div>
            </div>

            <div class="post-card">
                <img src="New Folder/33.jpg" alt="Post Image">
                <div class="post-content">
                    <h3>Kucing Berenang</h3>
                    <p>Sebuah Gambar Anak Kucing Yang Sedang Berenang</p>
                </div>
            </div>
        </div> -->
    </div>
    <footer>
        <p>&copy; 2024 All About Art. Semua Hak Dilindungi.</p>
    </footer>
    <script src="popup.js"></script>
</body>
<style>
body {
    font-family: 'Montserrat', sans-serif;
    margin: 0; /* Remove default margin */
    line-height: 1.6; /* Improve line height for readability */
}

        /* Header */
        header {
    background-color: #3C0753;
    padding: 15px 20px; /* Add padding to the header */
    position: relative; /* Allow absolute positioning for child elements */
}

nav {
    display: flex;
    justify-content: space-around; /* Adjust for space between items */
    align-items: center; /* Center items vertically */
}

h1 {
    color: white; /* Change heading color */
    margin: 0; /* Remove default margin */
}

nav a {
    text-decoration: none;
    color: #fff;
    font-weight: bold;
    padding: 5px 5px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

nav a:hover {
    background-color: #910A67;
}

footer {
            background-color: #030637;
            color: white;
            text-align: center;
            padding: 1px;
            width: 100%;
            margin-top: auto;
            bottom: 0;
            position: fixed;
        }

        /* Profile Container */
        .profile-container {
            width: 100%;
            max-width: 1600px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Profile Header */
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .profile-pic {
            flex: 1;
            display: flex;
            justify-content: center;
        }

        .profile-pic img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ddd;
        }

        .profile-info {
            flex: 2;
            padding-left: 20px;
        }

        .username {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .profile-stats {
            display: flex;
            justify-content: space-between;
            max-width: 300px;
            margin-bottom: 10px;
        }

        .stat {
            text-align: center;
        }

        .stat h3 {
            font-size: 18px;
            font-weight: bold;
        }

        .stat p {
            font-size: 14px;
            color: #666;
        }

        .profile-bio {
            margin-bottom: 10px;
        }

        .profile-bio p {
            font-size: 14px;
            color: #333;
        }

        .edit-profile-btn {
            padding: 8px 16px;
            font-size: 14px;
            background-color: #3C0753;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .edit-profile-btn:hover {
            background-color: #002f3b;
        }

        /* Profile Menu */
        .profile-menu {
            display: flex;
            justify-content: space-around;
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }

        .gallery {
            padding: 20px 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 5px;
            width: 100%;
            max-width: 1920px;
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
        }

        .gallery-item img {
            width: 400px;
            height: 300px;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .gallery-item img:hover {
            transform: scale(1.05);
        }

        .gallery-item video {
            width: 400px;
            height: 300px;
            position: relative;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .gallery-item video:hover {
            transform: scale(1.05);
        }

        .buttons {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            flex-direction: column;
            gap: 5px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .gallery-item:hover .buttons {
            opacity: 1;
        }

        /* Posts Section */
        .posts {
            margin-top: 20px;
        }

        .posts h2 {
            margin-bottom: 15px;
            font-size: 20px;
        }

        /* Post Card */
        .post-card {
            display: flex;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 15px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .post-card img {
            width: 150px;
            height: 150px;
            object-fit: cover;
        }

        .post-content {
            padding: 10px;
        }

        .post-content h3 {
            font-size: 18px;
            margin: 0 0 10px;
        }

        .post-content p {
            font-size: 14px;
            color: #666;
        }

        .post-button {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 12px;
            background-color: #0095f6;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .post-button:hover {
            background-color: #007ab8;
        }

        .favorites {
            margin-top: 40px;
        }

        .favorites h2 {
            margin-bottom: 15px;
            font-size: 20px;
        }

        /* Favorite Card */
        .favorite-card {
            display: flex;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 15px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
        background-color: #fff;
        margin: 15% auto; /* Pusatkan pop-up */
        border: 1px solid #888;
        padding: 10px;
        width: fit-content; /* Lebar pop-up */
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    .popup-content h2 {
        margin-top: 50px;
        margin-left: 50px;
        margin-right: 50px;
        text-align: center;
    }

    .popup-content p {
        text-align: center;
    }

    .popup-content a {
        text-decoration: none;
        color: white;
        background-color: #3C0753;
        padding: 10px 20px;
        border-radius: 5px;
        display: inline-block;
        margin-top: 10px;
        margin-bottom: 20px;
        margin-left: 6px;
    }

    .popup-content a:hover {
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
    </style>
</html>