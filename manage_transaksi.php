<?php
// Sambungan database
$hostname = "localhost";
$username = "root";
$password = "taqin2345";
$database_name = "allaboutartv2";

$db = mysqli_connect($hostname, $username, $password, $database_name);
session_start();
$username = $_SESSION['username'];

if ($db->connect_error) {
    die("Koneksi database gagal");
}

// Retrieve the Name
$result_admin = mysqli_query($db, "SELECT * FROM users_admin WHERE username = '$username'");
if ($result_admin && mysqli_num_rows($result_admin) > 0) {
    $data = mysqli_fetch_assoc($result_admin);
    $user_name = $data['name_admin']; // Get the user Name
}

// Hapus transaksi
if (isset($_GET['delete'])) {
    $id_transaksi = $_GET['delete'];
    $db->query("DELETE FROM transaksi WHERE id_transaksi=$id_transaksi");
    header("Location: manage_transaksi.php");
}

// Update data transaksi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id_transaksi = $_POST['id_transaksi'];
    $status_transaksi = $_POST['status_transaksi'];
    $jenis_transaksi = $_POST['jenis_transaksi'];
    $db->query("UPDATE transaksi SET status_transaksi='$status_transaksi', jenis_transaksi='$jenis_transaksi' WHERE id_transaksi=$id_transaksi");
    header("Location: manage_transaksi.php");
}

// Ambil semua data transaksi
$result = $db->query(
    "SELECT transaksi.id_transaksi, post.name_post, users_app.name_user, transaksi.total_bayar, transaksi.status_transaksi, transaksi.waktu_transaksi, transaksi.jenis_transaksi " .
    "FROM transaksi " .
    "INNER JOIN post ON transaksi.id_post = post.id_post " .
    "INNER JOIN users_app ON transaksi.id_users_app = users_app.id_users_app"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi</title>
</head>
<body>
    <!-- Header -->
    <header>
        <nav>
            <h1><a href="index.php">All About Art</a></h1>
            <a href="logout_admin.php">Logout</a>
            <a><?php echo $user_name ?></a>
        </nav>
    </header>

    <h2>Kelola Transaksi</h2>

    <table>
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>Nama Postingan</th>
                <th>Pembeli</th>
                <th>Total Bayar</th>
                <th>Status Transaksi</th>
                <th>Waktu Transaksi</th>
                <th>Jenis Transaksi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id_transaksi'] ?></td>
                    <td><?= $row['name_post'] ?></td>
                    <td><?= $row['name_user'] ?></td>
                    <td>Rp<?= number_format($row['total_bayar'], 0, ',', '.') ?></td>
                    <form method="POST">
                        <td>
                            <input type="hidden" name="id_transaksi" value="<?= $row['id_transaksi'] ?>">
                            <select name="status_transaksi" required>
                                <option value="pending" <?= $row['status_transaksi'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="selesai" <?= $row['status_transaksi'] === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                            </select>
                        </td>
                        <td><?= $row['waktu_transaksi'] ?></td>
                        <td>
                            <select name="jenis_transaksi" required>
                                <option value="penjualan" <?= $row['jenis_transaksi'] === 'penjualan' ? 'selected' : '' ?>>penjualan</option>
                                <option value="pembelian" <?= $row['jenis_transaksi'] === 'pembelian' ? 'selected' : '' ?>>pembelian</option>
                            </select>
                        </td>
                        <td>
                            <button type="submit" name="update">Update</button>
                            <a href="?delete=<?= $row['id_transaksi'] ?>" onclick="return confirm('Apakah Anda yakin?')">Hapus</a>
                        </td>
                    </form>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 All About Art. Semua Hak Dilindungi.</p>
    </footer>
</body>
</html>

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

/* Isi */
h2 {
    text-align: center;
}

form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        form input, form button {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        form input {
            flex: 1 1 auto;
            max-width: 200px;
        }
        form button {
            background-color: #3C0753;
            color: white;
            cursor: pointer;
            border: none;
        }
        form button:hover {
            background-color: #0056b3;
        }
        table {
            width: 75%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-left: auto;
            margin-right: auto;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
            color: #333;
            font-weight: bold;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tbody tr:hover {
            background-color: #f1f1f1;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }

/* Footer */
footer {
    background-color: #030637;
    color: white;
    text-align: center;
    padding: 1px;
    margin-top: 40px;
    bottom: 0;
    position: fixed;
    width: 100%;
}


</style>