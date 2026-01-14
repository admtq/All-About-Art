<?php
session_start();
if(isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('location: index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <header>
        <h1>All About Art</h1>
    </header>

    <div class="dashboard">
        <h1>Apakah Anda Yakin Untuk Log Out?</h1>
        <div class="logout">
            <form action="logout.php" method="POST">
                <button type="submit" name ="logout">Ya</button>
            </form>
        </div>
        <a href="profile.php">Tidak</a>
    </div>
</body>
<style>
        header {
            background-color: #3C0753;
            color: white;
            text-align: center;
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            
        }
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
        }

        .dashboard {
            padding: 50px;
        }

        .logout {
            text-decoration: none;
            background-color: #3C0753;
            color: white;
            padding: 8px 24px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }

        .logout:hover {
            background-color: #002f3b;
        }

        .dashboard a {
            text-decoration: none;
            color: white;
            background-color: #3C0753;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }

        .dashboard a:hover {
            background-color: #002f3b;
        }
    </style>
</html>