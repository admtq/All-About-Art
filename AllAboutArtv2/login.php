<?php
    include "db.php";
    session_start();

    $signin_message = "";

    if(isset($_SESSION["is_login"]) == true) {
        header("location: home_after_login.php");
    }

    if(isset($_POST['signin'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $sql = "SELECT * FROM users_app WHERE username='$username' AND password='$password'";
        $result = $db->query($sql);
        if($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            $_SESSION["username"] = $data["username"];
            $_SESSION["is_login"] = true;
            header("location: home_after_login.php");
        }else {
            $signin_message = "Akun tidak ditemukan";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk</title>
</head>
<body>
    <!-- Header -->
    <header>
        <nav>
            <h1><a href="index.php">All About Art</a></h1>
        </nav>
    </header>

    <section class="login">
        <div class="login-form">
            <h2>Login</h2>
            <form action="login.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <i><?= $signin_message ?></i>
                <button type="submit" name="signin">Login</button>
                <a>Belum punya akun?</a>
                <a href="register.php">klik disini</a>
            </form>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 All About Art. Semua Hak Dilindungi.</p>
    </footer>
</body>
<style>
        /* Basic Styling */
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0; /* Remove default margin */
            line-height: 1.6; /* Improve line height for readability */
            background-color: #f4f4f4; /* Background color */
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
            padding: 5px 10px; /* Adjust padding */
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background-color: #003735;
        }

        .login-form {
            background-color: white;
            padding: 30px;
            max-width: 400px;
            margin: 40px auto;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .login-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-form input {
            width: 94%;
            padding: 10px;
            margin: 10px 0px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .login-form button {
            width: 100%;
            padding: 10px;
            background-color: #3C0753; /* Header color */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-bottom: 25px;
        }

        .login-form button:hover {
            background-color: #002f3b; /* Darker shade for hover */
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