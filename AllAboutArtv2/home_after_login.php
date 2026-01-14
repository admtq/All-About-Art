<?php
session_start();
include 'db.php'; // Pastikan koneksi database

$username = $_SESSION['username'];
// Retrieve the Name
$result = mysqli_query($db, "SELECT * FROM users_app WHERE username = '$username'");
if ($result && mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
    $user_id = $data['id_users_app']; // get uswr id
} else {
    header("location: login.php");
}

// Pastikan pengguna sudah login
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Perbarui data aktivitas pengguna
    $update_query = "INSERT INTO active_users (user_id, last_activity)
                     VALUES ($user_id, NOW())
                     ON DUPLICATE KEY UPDATE last_activity = NOW()";
    $db->query($update_query);
}


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

$heroCategory = 'fotografi';
$heroImages = $data[$heroCategory] ?? []; // Ambil data dari kategori "fotografi"
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda</title>
</head>
<body>
    <!-- Header -->
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
    <!-- Hero Section -->
    <section class="hero">
        <?php if (!empty($heroImages)): ?>
            <?php foreach ($heroImages as $index => $image): ?>
                <img src="dbpost/db<?= $heroCategory; ?>/<?= $image['media_file']; ?>" 
                    alt="Hero Image <?= $index + 1; ?>" 
                    class="slider-image" 
                    style="opacity: <?= $index === 0 ? '1' : '0'; ?>;">
            <?php endforeach; ?>
        <?php else: ?>
            <p>No images available for category <?= ucfirst($heroCategory); ?>.</p>
        <?php endif; ?>
        <div class="hero-text">
            <h1><b>Selamat Datang di All About Art</b></h1>
            <p><b>Temukan Seni Terbaik dari Berbagai Dunia</b></p>
        </div>
        <button class="prev" onclick="changeSlide(-1)">&#10094;</button>
        <button class="next" onclick="changeSlide(1)">&#10095;</button>
    </section>



    <!-- Categories Section -->
    <section class="categories">
    <h2>Kategori Umum</h2>
    <div class="category-list">

        <?php foreach ($categories as $category): ?>
            <!-- Tambahkan link untuk setiap kategori -->
            <a href="<?= $category; ?>_list.php">
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
<!-- Gallery Section -->
<!-- <section class="gallery">
    <div class="gallery-heading">
        <h2>ON TRENDING!!</h2>
    </div>
    <div class="gallery-grid">
        <div class="gallery-item">
            <img src="New Folder/1.jpg" alt="Gambar 1">
            <p>Pemandangan malam yang tenang dan menenangkan di pegunungan...</p>
        </div>
        <div class="gallery-item">
            <img src="New Folder/vincent.jpg" alt="Gambar 2">
            <p>Meme Pak Vincent</p>
        </div>
        <div class="gallery-item">
            <img src="New Folder/2.jpeg" alt="Gambar 3">
            <p>Sebuah danau yang tenang dengan permukaan air...</p>
        </div>
    </div>
</section> -->


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

/* Hero Section */
.hero {
    position: relative;
    height: 400px;
    overflow: hidden;
    text-align: center;
}

.hero img {
    width: 100%;
    height: 400px;
    object-fit: cover;
    position: absolute;
    left: 0;
    top: 0;
    transition: opacity 1s ease;
}

.hero-text {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    color: white;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
}

.hero-text h1 {
    font-size: 58px; /* Reduce font size for better fit */
    margin: 10px;
}

.hero-text p {
    font-size: 28px; /* Reduce font size for better fit */
    margin-top: 10px;
    
}

/* Navigation Buttons */
.prev, .next {
    cursor: pointer;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    color: white;
    background-color: rgba(0, 0, 0, 0.5);
    border: none;
    padding: 10px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.prev:hover, .next:hover {
    background-color: rgba(0, 0, 0, 0.8);
}

.prev {
    left: 20px;
}

.next {
    right: 20px;
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

/* Gallery Section */
.gallery {
    padding: 30px 20px;
    background-color: #f4f4f4;
    text-align: center;
}

.gallery-heading {
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px 20px;
    border-radius: 5px;
    margin-bottom: 20px;
    background-color: #3C0753; /* Added background for contrast */
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    
}

.gallery-item {
    background-color: rgb(233, 202, 243);
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

.gallery-item:hover img {
    transform: scale(1.1);
}

/* Video */
.video {
    width: 100%; /* Responsive video width */
    border-radius: 10px;
}
.gallery-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px; /* Mengatur jarak antar item */
}

.gallery-item {
    flex: 1 1 300px; /* Ukuran dasar item, bisa disesuaikan */
    max-width: 400px; /* Memastikan lebar maksimum item */
}

.gallery-item img {
    width: 100%; /* Membuat gambar memenuhi lebar item */
    height: 400px; /* Menentukan tinggi yang konsisten */
    object-fit: cover; /* Memotong gambar agar sesuai proporsi tanpa merusak */
}
.p {
    font-size: 28px;
}
/* Footer */
footer {
    background-color: #030637;
    color: white;
    text-align: center;
    padding: 20px;
    margin-top: 40px;
    bottom: 0;
}

/* Media Queries */
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