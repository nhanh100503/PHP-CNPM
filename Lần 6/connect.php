<?php
    $servername = "localhost:3307";
    $username = "root";
    $password = "";
    $dbname = "quanly6";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (mysqli_connect_error()){
        die("Lỗi kết nối " . mysqli_error($conn));
    }