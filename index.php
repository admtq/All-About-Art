<?php
// Sambungan database
$hostname = "localhost";
$username = "root";
$password = "taqin2345";
$database_name = "allaboutartv2";

$db = mysqli_connect($hostname, $username, $password, $database_name);

session_start();
$username = $_SESSION['username'];

if($db->connect_error) {
    echo "koneksi database gagal";
    die("error!");
}

// Retrieve the Name
$result_admin = mysqli_query($db, "SELECT * FROM users_admin WHERE username = '$username'");
if ($result_admin && mysqli_num_rows($result_admin) > 0) {
    $data = mysqli_fetch_assoc($result_admin);
    $user_name = $data['name_admin']; // Get the user Name
} else {
    header("location: login_admin.php");
}

?>

<!-- Jumlah Pengguna -->
<?php
$sql = "SELECT COUNT(*) AS total FROM users_app";
$result = $db->query($sql);

// Ambil hasil
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $jumlahDataUsers = $row['total'];
} else {
    $jumlahDataUsers = 0;
}

?>

<!-- Jumlah Postingan -->
<?php
// Tabel-tabel yang akan dihitung jumlah datanya
$tables = ['post'];
$jumlahpostingan = 0;

foreach ($tables as $table) {
    $result = $db->query("SELECT COUNT(*) AS count FROM $table");
    if ($result) {
        $row = $result->fetch_assoc();
        $jumlahpostingan += $row['count'];
    }
}

// Mencatat setiap kunjungan ke halaman
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip_address = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip_address = $_SERVER['REMOTE_ADDR'];
}

// $visit_date = date('Y-m-d H:i:s');     // Mengambil tanggal dan waktu kunjungan
$visit_date = date('Y-m-d');

// Menyimpan data kunjungan ke dalam database setiap kali halaman diakses
$insert_query = "INSERT INTO visits (ip_address, visit_date) VALUES ('$ip_address', '$visit_date')";
$db->query($insert_query);

$sql = "SELECT visit_date, COUNT(*) AS total FROM visits GROUP BY visit_date ORDER BY visit_date ASC";
$result = $db->query($sql);
$visitor_data = [];

while ($row = $result->fetch_assoc()) {
    $visitor_data[] = ['date' => $row['visit_date'], 'count' => $row['total']];
}


// Format data untuk Chart.js
$dates = [];
$counts = [];

foreach ($visitor_data as $data) {
    $dates[] = $data['date'];
    $counts[] = $data['count'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Header -->
    <header>
        <nav>
            <h1><a href="index.php">All About Art</a></h1>
            <a></a>
            <a></a>
            <a></a>
            <a></a>
            <a href="logout_admin.php">Logout</a>
            <a><?php echo $user_name ?></a>
        </nav>
    </header>

    <!-- Dashboard Summary Section -->
    <section class="dashboard-summary">
        <h2>Statistik Aktivitas Website</h2>
        <div class="summary-container">
            <div class="summary-box">
                <h3>Jumlah Pengguna</h3>
                <p><?php
                $count_query = "SELECT COUNT(*) AS active_users FROM active_users";
                $result = $db->query($count_query);
                $row = $result->fetch_assoc();
                echo $jumlahDataUsers; ?></p>
            </div>
            <div class="summary-box">
                <h3>Pengguna Aktif</h3>
                <p><?php echo $row['active_users']; ?></p>
            </div>
            <div class="summary-box">
                <h3>Jumlah Postingan</h3>
                <p><?php echo $jumlahpostingan; ?></p>
            </div>
        </div>
    </section>

    <!-- Visitor Chart Section -->
    <section class="visitor-chart">
        <h2>Grafik Pengunjung Per Hari</h2>
        <div style="width: 40%; margin: 0 auto;">
            <canvas id="visitorChart"></canvas>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories">
        <h2>Kelola Website</h2>
        <div class="category-list">
            <div class="category-item">
                <a href="manage_users.php"><img src="AllAboutArtv2/icon/profile.png" alt="User"></a>
                <p>Akun Pengguna</p>
            </div>
            <div class="category-item">
                <a href="manage_transaksi.php"><img src="AllAboutArtv2/icon/transaksi.png" alt="Vector"></a>
                <p>Daftar Transaksi</p>
            </div>
            <div class="category-item">
                <a href="manage_fotografi.php"><img src="AllAboutArtv2/icon/fotografi.png" alt="Fotografi"></a>
                <p>Postingan Fotografi</p>
            </div>
            <div class="category-item">
                <a href="manage_videografi.php"><img src="AllAboutArtv2/icon/video-camera.png" alt="Videografi"></a>
                <p>Postingan Videografi</p>
            </div>
            <div class="category-item">
                <a href="manage_animasi.php"><img src="AllAboutArtv2/icon/cuts.png" alt="Animasi"></a>
                <p>Postingan Animasi</p>
            </div>
            <div class="category-item">
                <a href="manage_vector.php"><img src="AllAboutArtv2/icon/hobbies.png" alt="Vector"></a>
                <p>Postingan Vector</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 All About Art. Semua Hak Dilindungi.</p>
    </footer>

    <!-- Chart.js Script -->
    <script>
    const ctx = document.getElementById('visitorChart').getContext('2d');
    const visitorChart = new Chart(ctx, {
        type: 'line', // Line chart
        data: {
            labels: <?php echo json_encode($dates); ?>, // Tanggal pengunjung
            datasets: [{
                label: 'Jumlah Pengunjung',
                data: <?php echo json_encode($counts); ?>, // Jumlah pengunjung per tanggal
                backgroundColor: 'rgba(60, 7, 83, 0.2)', // Transparent purple background
                borderColor: 'rgba(60, 7, 83, 1)', // Purple border
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Tanggal'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Jumlah Pengunjung'
                    },
                    beginAtZero: true
                }
            }
        }
    });
</script>

</body>
<style>
/* Basic Styling */
body {
    font-family: 'Montserrat', sans-serif;
    margin: 0;
    line-height: 1.6;
}

/* Header */
header {
    background-color: #3C0753;
    padding: 15px 20px;
}

nav {
    display: flex;
    justify-content: space-around;
    align-items: center;
}

h1 {
    color: white;
    margin: 0;
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

.button-container {
        display: flex;
        justify-content: space-between;
    }

    .button-container button {
        width: 48%;
        padding: 5px 5px;
        background-color: #3C0753;
        text-decoration: none;
        color: #fff;
        font-weight: bold;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .button-container button:hover {
        background-color: #910A67;
    }

/* Visitor Chart Section */
.visitor-chart {
    padding: 30px 20px;
    background-color: #f4f4f4;
    text-align: center;
}

.visitor-chart h2 {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
}

/* Dashboard Summary Section */
.dashboard-summary {
    background-color: #f4f4f4;
    padding: 30px 20px;
    text-align: center;
}

.dashboard-summary h2 {
    font-size: 24px;
    margin-bottom: 20px;
    font-weight: bold;
}

.summary-container {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

.summary-box {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 200px;
    font-size: 18px;
}

.summary-box h3 {
    font-weight: bold;
    margin-bottom: 10px;
}

.summary-box p {
    font-size: 24px;
    color: #3C0753;
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
    gap: 20px;
    flex-wrap: wrap;
}

.category-item {
    background-color: rgb(233, 202, 243);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
    width: 200px;
}

.category-item:hover {
    transform: translateY(-10px);
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
}

.category-item img {
    max-width: 100%;
    border-radius: 10px;
}

/* Footer */
footer {
    background-color: #030637;
    color: white;
    text-align: center;
    padding: 20px;
    margin-top: 40px;
}
</style>
</html>