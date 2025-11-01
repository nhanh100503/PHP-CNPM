<?php
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "quanly";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}

// echo "Kết nối thành công";