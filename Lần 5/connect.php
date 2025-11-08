<?php
    $servername = "localhost:3307";
    $username = "root";
    $password = "";
    $dbname = "quanly5";

    // Thiết lập kết nối
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (mysqli_connect_error()){
        die("Lỗi kết nối " . $conn->error);
    }
?>