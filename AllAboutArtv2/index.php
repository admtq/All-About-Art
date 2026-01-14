<?php
include 'db.php'; // Pastikan koneksi database

$categories = ['fotografi', 'videografi', 'animasi', 'vector'];
$data = [];

$query = "SELECT id_post, media_file FROM post_media";
$result = mysqli_query($db, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        foreach ($categories as $category) {
            if (preg_match("/^{$category}_/i", $row['media_file'])) {
                $data[$category][] = $row;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All About Art</title>
</head>
<body>
    <!-- Header -->
    <header>
        <nav>
            <h1><a href="index.php">All About Art</a></h1>
            <a href="login.php">Masuk</a>
        </nav>
    </header>

    <!-- Search Bar -->
    <!-- <div class="text"><b>Cari Gambar Yang Anda Inginkan!</b></div>
    <div class="search-bar">
        <input type="text" id="search-input" placeholder="Cari gambar...">
        <button type="submit" id="search-button">Cari</button>
    </div> -->

    <!-- Categories Section -->
    <main>
    <section class="categories">
    <h2>Kategori Umum</h2>
    <div class="category-list">

        <?php foreach ($categories as $category): ?>
            <!-- Tambahkan link untuk setiap kategori -->
            <a href="<?= $category; ?>_list_logout.php">
                <div class="category-item">
                    <h3><?= ucfirst($category); ?></h3>
                    <div class="slideshow-container" id="slideshow-<?= $category; ?>">
                        <?php if (!empty($data[$category])): ?>
                            <?php foreach ($data[$category] as $index => $media): ?>
                                <div class="slide <?= $index === 0 ? 'active' : ''; ?>">
                                    <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $media['media_file'])): ?>
                                        <img src="dbpost/db<?= $category; ?>/<?= $media['media_file']; ?>" alt="<?= $media['media_file']; ?>" style="width: 100%;">
                                    <?php elseif (preg_match('/\.(mp4|webm|ogg)$/i', $media['media_file'])): ?>
                                        <video controls style="width: 100%;">
                                            <source src="dbpost/db<?= $category; ?>/<?= $media['media_file']; ?>" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No media available for <?= ucfirst($category); ?></p>
                        <?php endif; ?>
                    </div>
                    <button class="prev" onclick="changeSlide('<?= $category; ?>', -1)">&#10094;</button>
                    <button class="next" onclick="changeSlide('<?= $category; ?>', 1)">&#10095;</button>
                </div>
            </a>
        <?php endforeach; ?>

    </div>
</section>

        <!-- Gallery Section -->

        <!-- <section class="gallery">
            <div class="container">
                <h3>Foto dan Video Terbaru</h3>
                <div class="gallery-grid">
                    <div class="gallery-item">
                        <a href="sign in.php"><img src="New folder/ds 1.jpg" alt="Deskripsi Gambar 1"></a>
                        <p>Anime</p>
                    </div>
                    <div class="gallery-item">
                        <a href="sign in.php"><img src="New folder/ds 2.jpg" alt="Deskripsi Gambar 2"></a>
                        <p>Anime</p>
                    </div>
                    <div class="gallery-item">
                        <a href="sign in.php"><img src="New folder/ds 3.jpg" alt="Deskripsi Gambar 3"></a>
                        <p>Anime</p>
                    </div>
                    <div class="gallery-item">
                        <a href="sign in.php"><img src="New folder/33.jpg" alt="Deskripsi Gambar 5"></a>
                        <p>Vector Kucing</p>
                    </div>
                    <div class="gallery-item">
                        <a href="sign in.php"><img src="New Folder/Fotografi/hutan.jpg" alt="jalan"></a>
                        <p>Jalan Raya</p>
                    </div>
                    <div class="gallery-item">
                        <a href="sign in.php"><img src="New Folder/Fotografi/fotografi.jpg" alt="fotografi, jalan raya"></a>
                        <p>Langit</p>
                    </div>
                    <div class="gallery-item">
                        <a href="sign in.php"><img src="New Folder/Vector/4.jpg" alt="fotografi, jalan raya"></a>
                        <p>Sunset</p>
                    </div>
                </div>
            </div>
        </section> -->
    </main>
    <!-- Footer -->
    <footer>
        <p>&copy; 2024 All About Art. Semua Hak Dilindungi.</p>
    </footer>

    <script>
        // HERO SLIDESHOW
let heroSlideIndex = 0;
const heroSlides = document.querySelectorAll('.hero .slider-image');

function changeHeroSlide(direction) {
    heroSlides[heroSlideIndex].style.opacity = 0; // Hide current slide
    heroSlideIndex = (heroSlideIndex + direction + heroSlides.length) % heroSlides.length; // Calculate new slide index
    heroSlides[heroSlideIndex].style.opacity = 1; // Show new slide
}

// Automatic slide change for hero section every 5 seconds
setInterval(() => changeHeroSlide(1), 5000);

// CATEGORY SLIDESHOWS
const categorySlideshows = {};

document.querySelectorAll('.slideshow-container').forEach((container) => {
    const category = container.id.split('-')[1]; // Extract category from ID
    const slides = container.querySelectorAll('.slide');
    categorySlideshows[category] = { slides, currentIndex: 0 };
});

function changeCategorySlide(category, direction) {
    const { slides, currentIndex } = categorySlideshows[category];
    slides[currentIndex].classList.remove('active');
    categorySlideshows[category].currentIndex = (currentIndex + direction + slides.length) % slides.length;
    slides[categorySlideshows[category].currentIndex].classList.add('active');
}

// Optional: Auto-slide for categories every 5 seconds
setInterval(() => {
    Object.keys(categorySlideshows).forEach(category => {
        changeCategorySlide(category, 1);
    });
}, 3000);

    </script>
</body>
<style>
        /* Basic Styling */
        body {
    font-family: 'Montserrat', sans-serif;
    margin: 0; /* Remove default margin */
    line-height: 1.6; /* Improve line height for readability */
}


        /* Header */
        header {
            background-color: #3C0753; /* Updated header color */
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
            background-color: #003735;
        }

        /* Layout of Categories and Gallery */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .category-list, .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        /* Categories Section */
.categories {
    background-color: #f4f4f4;
    padding: 50px 20px;
    text-align: center;
}

.category-list {
    display: flex;
    justify-content: center;
    gap: 20px; /* Adjusted gap for spacing */
    flex-wrap: wrap; /* Allow items to wrap */
}

.category-list a {
    text-decoration: none;
    color: inherit; /* Warna teks mengikuti elemen */
    display: block; /* Agar seluruh elemen dapat diklik */
}

.category-item {
    position: relative;
    background-color: rgb(233, 202, 243);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
    width: 200px;
    font-size: 18px;
    font-style: bold;
}

.category-item:hover {
    transform: translateY(-10px);
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    
    
}

.category-item img, .category-item video {
    width: 150px;
    height: 300px;
    object-fit: cover;
    border-radius: 10px;
    background-color: rgb(233, 202, 243);

}

.category-item .prev,
.category-item .next {
    position: absolute;
    z-index: 10; /* Pastikan tombol berada di atas elemen lain */
    cursor: pointer;
}

.category-item:hover .prev,
.category-item:hover .next {
    visibility: visible;
}

.slideshow-container {
    position: relative;
    max-width: 100%;
    margin: auto;
    overflow: hidden;
}

.slide {
    display: none;
    transition: opacity 1s ease;
}

.slide.active {
    display: block;
}

.prev, .next {
    cursor: pointer;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px;
}

.prev {
    left: 10px;
}

.next {
    right: 10px;
}

.prev:hover, .next:hover {
    background-color: rgba(0, 0, 0, 0.8);
}

        /* Gallery Items */
        .gallery-item {
            background-color:rgb(233, 202, 243);
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
        }

        .gallery-item:hover {
            transform: scale(1.05);
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        }

        .gallery-item img {
            max-width: 100%;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }
        .gallery-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px; /* Mengatur jarak antar item */
}

.gallery-item {
    flex: 400px; /* Ukuran dasar item, bisa disesuaikan */
    max-width: 200x; /* Memastikan lebar maksimum item */
}

.gallery-item img {
    width: 100%; /* Membuat gambar memenuhi lebar item */
    height: 640px; /* Menentukan tinggi yang konsisten */
    object-fit: cover; /* Memotong gambar agar sesuai proporsi tanpa merusak */
}

        .gallery-item:hover img {
            transform: scale(1.1);
        }

        /* Search Bar */
        .text {
            text-align: center;
            padding: 20px;
        }

        .search-bar {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-bar input {
            padding: 10px;
            width: 300px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-right: 10px;
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

        /* Footer */
        footer {
            background-color: #030637;
            color: white;
            text-align: center;
            padding: 20px;
            width: 100%;
            margin-top: auto;
            bottom: 0;
            position: absolute;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            nav {
                flex-direction: column; /* Stack navigation items on small screens */
                align-items: center; /* Center items */
            }

            nav a {
                padding: 10px;
                border-bottom: 1px solid #f4f4f4;
            }
        }
    </style>
</html>