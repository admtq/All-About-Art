<?php
include "db.php";
session_start();
$upload_message = "";

// Ensure the user is logged in and get their ID
if (!isset($_SESSION['username'])) {
    die("You must be logged in to upload.");
}

$username = $_SESSION['username']; // Username from session

// Retrieve the user's ID
$result = mysqli_query($db, "SELECT id_users_app FROM users_app WHERE username = '$username'");
if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $user_id = $user['id_users_app']; // Get the user ID
} else {
    die("User not found.");
}

if (isset($_POST['submit'])) {
    $name = $_POST['name_post'];
    $caption = $_POST['caption_post'];
    $harga = $_POST['harga_post'];
    $filename = "fotografi_" . $_FILES['file']['name'];
    $tmpname = $_FILES['file']['tmp_name'];
    $folder = 'dbpost/dbfotografi/' .$filename;

    // Insert post into the database
    $querypost = mysqli_query($db, "INSERT INTO post (created_by_user_id, name_post, caption_post, harga_post) VALUES ('$user_id', '$name', '$caption', '$harga')");
    if (!$querypost) {
        die("Error inserting into post table: " . mysqli_error($db));
    }

    // Get the ID of the inserted post
    $id_post = mysqli_insert_id($db);

    // Insert media into the post_media table, linking it to the post
    $querymedia = mysqli_query($db, "INSERT INTO post_media (id_post, media_file) VALUES ('$id_post', '$filename')");
    if (!$querymedia) {
        die("Error inserting into post_media table: " . mysqli_error($db));
    }

    // Upload the file to the server
    if (move_uploaded_file($tmpname, $folder)) {
        header("Location: profile.php");
        exit;
    } else {
        $upload_message = "File upload failed.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posting Fotografi</title>
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
    <h2>Posting Fotografi</h1>
    <form action="upload_fotografi.php" method="POST" enctype="multipart/form-data">
        <label for="judul">Judul:</label><br>
        <input type="text" id="judul" name="name_post" required><br><br>

        <label for="deskripsi">Deskripsi:</label><br>
        <textarea id="deskripsi" name="caption_post" rows="3" required></textarea><br><br>

        <label for="judul">Harga:</label><br>
        <input type="text" id="harga" name="harga_post" required oninput="formatRupiah(this)" onblur="removeRp(this)"><br><br>

        <div id="drop-area">
            <p>Drag & drop file di sini atau klik untuk memilih file</p>
            <input type="file" id="file" name="file" accept=".jpeg, .jpg, .png, .svg, .bmp" required style="display: none;">
        </div>

        <div id="preview"></div>
        <i><?= $upload_message ?></i>
        <button type="submit" name="submit" class="upload-button">Upload</button>
    </form>
    <script>

        // script harga
        // Fungsi untuk memformat input dengan Rp dan titik
        function formatRupiah(input) {
            let value = input.value.replace(/[^0-9]/g, ""); // Hanya angka
            if (value) {
            input.value = "Rp " + parseInt(value).toLocaleString("id-ID"); // Format dengan Rp dan titik
            } else {
            input.value = ""; // Kosongkan jika tidak ada angka
            }
        }

        // Fungsi untuk menghapus Rp saat input kehilangan fokus
        function removeRp(input) {
            let value = input.value.replace(/[^0-9]/g, ""); // Hanya angka
            input.value = value; // Hanya angka yang tersimpan
        }

        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('file');
        const preview = document.getElementById('preview');

        // Mencegah perilaku default
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Menandai area drop saat di-drag
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });
        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            dropArea.classList.add('hover');
        }

        function unhighlight() {
            dropArea.classList.remove('hover');
        }

        // Menangani file yang di-drop
        dropArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files);
        }

        // Fungsi untuk menangani file
        function handleFiles(files) {
            if (files.length > 0) {
                const file = files[0];
                const reader = new FileReader();
                
                reader.onload = function(event) {
                    // const img = document.createElement("img");
                    // img.src = event.target.result;
                    const fileType = file.type;
                    preview.innerHTML = ""; // Kosongkan preview sebelumnya

                    if (fileType.startsWith('image/')) {
                        // Jika file adalah gambar
                        const img = document.createElement("img");
                        img.src = event.target.result;
                        preview.appendChild(img); // Tambahkan gambar ke preview
                    } else if (fileType.startsWith('video/')) {
                        // Jika file adalah video
                        const video = document.createElement("video");
                        video.src = event.target.result;
                        video.controls = true; // Menambahkan kontrol video
                        video.width = 300; // Atur lebar video sesuai kebutuhan
                        preview.appendChild(video); // Tambahkan video ke preview
                    } else {
                        alert("File yang diunggah bukan gambar atau video.");
                    }

                    // preview.appendChild(img); // Tambahkan gambar ke preview
                    dropArea.style.display = 'none'; // Sembunyikan area drop
                }
                
                reader.readAsDataURL(file); // Membaca file sebagai URL data
                fileInput.files = files; // Mengatur input file dengan file yang di-drop
            }
        }

        // Menangani klik pada area drop untuk membuka dialog pemilihan file
        dropArea.addEventListener('click', () => {
            fileInput.click();
        });

        // Menangani perubahan pada input file untuk preview
        fileInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });
    </script>
</body>
<style>
    * {
        box-sizing: border-box;
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

    h2 {
        text-align: center;
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
    body {
    font-family: 'Montserrat', sans-serif;
    margin: 0; /* Remove default margin */
    line-height: 1.6; /* Improve line height for readability */

    /* Box Upload */

    form {
        background-color: white; 
            padding: 30px;
            max-width: 400px;
            margin: 40px auto;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0px;
            border-radius: 10px;
            border: 1px solid #ccc;
        }

        textarea[id="deskripsi"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0px;
            border-radius: 10px;
            border: 1px solid #ccc;
        }

        #drop-area {
            border: 2px dashed #ccc;
            border-radius: 20px;
            width: 100%px;
            height: 150px;
            margin: 20px auto;
            text-align: center;
            padding: 20px;
        }
        #drop-area.hover {
            border-color: #333;
        }
        #preview {
            margin-top: 20px;
            text-align: center;
        }
        img {
            max-width: 100%;
            height: auto;
        }

        .upload-button {
            width: 25%;
            margin-left: 37%;
            margin-top: 30px;
            border: 2px solid #3C0753; /* Border hijau pada tombol upload */
            background-color: #3C0753;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .upload-button:hover {
            background-color: #002f3b; /* Mengubah warna latar belakang saat hover */
            color: white; /* Mengubah warna teks saat hover */
        }
}
</style>
</html>