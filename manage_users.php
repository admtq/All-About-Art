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

// Tambah data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $db->query("INSERT INTO users_app (name_user, username, password) VALUES ('$name', '$username', '$password')");
    header("Location: manage_users.php");
}

// Hapus data
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $db->query("DELETE FROM users_app WHERE id_users_app=$id");
    header("Location: manage_users.php");
}

// Ambil data untuk update
$edit_item = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $db->query("SELECT * FROM users_app WHERE id_users_app=$id");
    $edit_item = $result->fetch_assoc();
}

// Update data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $db->query("UPDATE users_app SET name_user='$name', username='$username', password='$password' WHERE id_users_app=$id");
    header("Location: manage_users.php");
}

// Ambil semua data
$result = $db->query("SELECT * FROM users_app");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Pengguna</title>
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

    <h2>Kelola Akun Pengguna</h2>

    <form method="POST">
        <input type="hidden" name="id" value="<?= $edit_item['id_users_app'] ?? '' ?>">
        <input type="text" name="name" placeholder="Name" value="<?= $edit_item['name_user'] ?? '' ?>" required>
        <input type="text" name="username" placeholder="Username" value="<?= $edit_item['username'] ?? '' ?>" required>
        <input type="text" name="password" placeholder="Password" value="<?= $edit_item['password'] ?? '' ?>" required>
        <?php if ($edit_item): ?>
            <button type="submit" name="update">Update</button>
        <?php else: ?>
            <button type="submit" name="create">Tambah</button>
        <?php endif; ?>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Username</th>
                <th>Password</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id_users_app'] ?></td>
                    <td><?= $row['name_user'] ?></td>
                    <td><?= $row['username'] ?></td>
                    <td><?= $row['password'] ?></td>
                    <td>
                        <a href="?edit=<?= $row['id_users_app'] ?>">Edit</a> |
                        <a href="?delete=<?= $row['id_users_app'] ?>" onclick="return confirm('Apakah ngana yakin?')">Hapus</a>
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