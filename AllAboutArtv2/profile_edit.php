<?php
include "db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name_user = htmlspecialchars(trim($_POST['name_user']));
    $description_user = htmlspecialchars(trim($_POST['description_user']));
    $phone_number = htmlspecialchars(trim($_POST['phone_number']));

    // Handle profile picture upload
    $foto_user = null;
    if (isset($_FILES['foto_user']) && $_FILES['foto_user']['error'] == 0) {
        $target_dir = "profileusers/";
        $file_name = basename($_FILES["foto_user"]["name"]);
        $target_file = $target_dir . time() . "_" . $file_name;

        if (move_uploaded_file($_FILES["foto_user"]["tmp_name"], $target_file)) {
            $foto_user = $target_file;
        }
    }

    $username = $_SESSION['username']; // Username from session

    // Retrieve user ID
    $result = mysqli_query($db, "SELECT * FROM users_app WHERE username = '$username'");
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        $user_id = $data['id_users_app'];
    } else {
        die("User not found.");
    }

    // Prepare SQL query for update
    if ($foto_user) {
        $sql = "UPDATE users_app SET 
                    name_user = ?, 
                    description_user = ?, 
                    notelepon = ?, 
                    foto_user = ? 
                WHERE id_users_app = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssssi", $name_user, $description_user, $phone_number, $foto_user, $user_id);
    } else {
        $sql = "UPDATE users_app SET 
                    name_user = ?, 
                    description_user = ?, 
                    notelepon = ? 
                WHERE id_users_app = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sssi", $name_user, $description_user, $phone_number, $user_id);
    }

    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['message'] = "Profile updated successfully.";
        header("Location: profile.php"); // Redirect to profile page
        exit;
    } else {
        echo "Error updating profile: " . $db->error;
    }

    $stmt->close();
}

$username = $_SESSION['username']; // Username from session
// Retrieve user Info
$result = mysqli_query($db, "SELECT * FROM users_app WHERE username = '$username'");
if ($result && mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
    $name_now = $data['name_user'];
    $description_now = $data['description_user'];
    $phone_now = $data['notelepon'];
    $foto_now = $data['foto_user'];
} else {
    die("User not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
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

    <!-- Edit Profil Section -->
    <section class="edit-profile">
        <div class="edit-profile-container">
            <!-- Profile Picture Section -->
            <div class="profile-pic-container">
            <div class="profile-pic">
                <img id="preview" src="<?php echo $foto_now ?: 'profileusers/default.png' ?>" alt="Profile">
            </div>
                <label for="profile-picture-upload" class="camera-icon">
                    <i class="fas fa-camera"></i>
                </label>
            </div>

            <!-- Edit Form Section -->
            <form action="profile_edit.php" method="POST" enctype="multipart/form-data" class="edit-form">
                <input type="text" name="name_user" placeholder="Nama Akun" value="<?php echo $name_now ?>" required>
                <input type="text" name="description_user" placeholder="Deskripsi" value="<?php echo $description_now ?>" required>
                <input type="text" name="phone_number" placeholder="Nomor Telepon" value="<?php echo $phone_now ?>" required>
                <input type="file" name="foto_user" id="profile-picture-upload" accept=".jpeg, .jpg" style="display: none;" onchange="previewImage(event)">
                <button type="submit" name="save_profile">Simpan</button>
            </form>

        </div>
    </section>

    <footer>
        <p>&copy; 2024 All About Art. Semua Hak Dilindungi.</p>
    </footer>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const output = document.getElementById('preview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

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
    color: white; /* Change heading color */
    margin: 0; /* Remove default margin */
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

    /* Edit Profile Section */
    .edit-profile {
        margin: 40px auto;
        max-width: 400px;
        background-color: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
    }

    .edit-profile h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .edit-form input {
        width: 94%;
        padding: 10px;
        margin: 10px 0px;
        border-radius: 10px;
        border: 1px solid #ccc;
    }

    .edit-form button {
        width: 100%;
        padding: 10px;
        background-color: #3C0753;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-top: 20px;
    }

    .edit-profile-form button:hover {
        background-color: #002f3b;
    }

    .edit-profile-container {
    text-align: center;
}

    /* Profile Picture Section */
.profile-pic-container {
    position: relative;
    display: inline-block;
    margin-bottom: 20px;
}

.profile-pic {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    overflow: hidden;
    position: relative;
    border: 2px solid #ddd;
}

.profile-pic img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Camera Icon */
.camera-icon {
    position: absolute;
    bottom: -10px; /* Geser keluar lingkaran */
    right: 10px;
    background-color: #3C0753;
    color: white;
    padding: 10px;
    border-radius: 50%;
    font-size: 16px;
    cursor: pointer;
    z-index: 2; /* Pastikan berada di atas gambar */
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    border: 2px solid white; /* Tambahkan border agar lebih jelas */
}

.camera-icon:hover {
    background-color: #910A67;
}

    footer {
        background-color: #030637;
        color: white;
        text-align: center;
        padding: 20px;
        margin-top: 40px;
        bottom: 0;
        width: 100%;
        position: absolute;
    }
</style>
</html>