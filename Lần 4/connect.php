<?php
    $servername = "localhost:3307";
    $username = "root";
    $password = "";
    $dbname = "quanly4";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if($conn->connect_error){
        die("Lỗi kết nối " . $conn->error);
    }