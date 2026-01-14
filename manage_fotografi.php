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
}

// Hapus data
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $db->query("DELETE FROM post WHERE id_post=$id");
    header("Location: manage_fotografi.php");
}

// Ambil data untuk update
$edit_item = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $db->query("SELECT * FROM post WHERE id_post=$id");
    $edit_item = $result->fetch_assoc();
}

// Update data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $judul = $_POST['nama_foto'];
    $deskripsi = $_POST['deskripsi_foto'];
    $db->query("UPDATE post SET name_post='$judul', caption_post='$deskripsi' WHERE id_post=$id");
    header("Location: manage_fotografi.php");
}

// Ambil semua data
$result = $db->query("SELECT post.id_post, post.name_post, post.caption_post, post_media.media_file FROM post_media INNER JOIN post ON post_media.id_post=post.id_post WHERE media_file LIKE '%fotografi%'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postingan Fotografi</title>
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

    <h2>Kelola Postingan Fotografi</h2>

    <?php if ($edit_item): ?>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $edit_item['id_post'] ?? '' ?>">
            <input type="text" name="nama_foto" placeholder="Judul Foto" value="<?= $edit_item['name_post'] ?? '' ?>" required>
            <input type="text" name="deskripsi_foto" placeholder="Deskripsi" value="<?= $edit_item['caption_post'] ?? '' ?>" required>
            <button type="submit" name="update">Update</button>
        </form>
    <?php else: ?>
        <p></p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Preview</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id_post'] ?></td>
                    <td><?= $row['name_post'] ?></td>
                    <td><?= $row['caption_post'] ?></td>
                    <td><img src="AllAboutArtv2/dbpost/dbfotografi/<?php echo $row['media_file']; ?>" alt="<?php echo $row['name_post']; ?>" style='width: 300px;'></td>
                    <td>
                        <a href="?edit=<?= $row['id_post'] ?>">Edit</a> |
                        <a href="?delete=<?= $row['id_post'] ?>" onclick="return confirm('Apakah ngana yakin?')">Hapus</a>
                    </td>
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