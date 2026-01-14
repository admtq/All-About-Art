<?php
    include "db.php";
    session_start();

    // $sql = "SELECT * FROM post_media WHERE media_file LIKE '%fotografi%'";
    // $result = mysqli_query($db, $sql);

    // Jika ada input pencarian
    if (isset($_POST['search_query']) && !empty($_POST['search_query'])) {
        $search_query = mysqli_real_escape_string($db, $_POST['search_query']);
        $sql = "SELECT * FROM post_media WHERE media_file LIKE '%$search_query%'";
    } else {
        // Query default jika tidak ada pencarian
        $sql = "SELECT * FROM post_media WHERE media_file LIKE '%fotografi%'";
    }

    $result = mysqli_query($db, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fotografi</title>
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

    <section class="gallery-section">
        <div class="search-bar">
            <!-- <input type="text" id="search-input" placeholder="Cari gambar..." onkeyup="searchImages()"> -->
            <form method="POST">
                <input type="text" name="search_query" id="search-input" placeholder="Cari Disini..." />
                <button type="submit">Cari</button>
            </form>
        </div>
        <h1>Disarankan Untuk Anda</h1>
        <div class="gallery" id="gallery">
            <?php 
            while($row = mysqli_fetch_assoc($result))
            {
            ?>
            <div class="gallery-item">
                <a href="fotografi_view.php?id_post=<?php echo $row['id_post']; ?>"><img src="dbpost/dbfotografi/<?php echo $row['media_file']; ?>" alt="<?php echo $row['media_file']; ?>"></a>
                <div class="buttons">
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    </section>
    
    <footer>
        <p>&copy; 2024 All About Art. Semua Hak Dilindungi.</p>
    </footer>
</body>
<style>
body {
    font-family: 'Montserrat', sans-serif;
    margin: 0; /* Remove default margin */
    line-height: 1.6; /* Improve line height for readability */
}

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
            padding: 20px;
            width: 100%;
            margin-top: auto;
            bottom:0 ;
            position: relative;
        }
        .gallery-section {
            width: 100%;
            text-align: center;
            margin-top: 20px;
        }

        .gallery-section h1 {
            margin-bottom: 20px;
            font-size: 2rem;
            color: #333;
        }

        .search-bar {
            margin-bottom: 20px;
        }

        .search-bar input {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
        }

        .search-bar button {
            padding: 10px 20px;
            background-color: #3C0753; /* Updated button color */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-bar button:hover {
            background-color: #004d4a;
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

        .like-btn, .fav-btn {
            background-color: rgba(0, 0, 0, 0.6);
            border: none;
            color: white;
            padding: 10px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 16px;
        }

        .like-btn:hover, .fav-btn:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        .like-btn:active, .fav-btn:active {
            transform: scale(0.9);
        }
    </style>
</html>