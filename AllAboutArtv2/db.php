<?php

$hostname = "localhost";
$username = "root";
$password = "taqin2345";
$database_name = "allaboutartv2";

$db = mysqli_connect($hostname, $username, $password, $database_name);

if($db->connect_error) {
    echo "koneksi database gagal";
    die("error!");
}

?>