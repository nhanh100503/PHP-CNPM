<?php
    require("connect.php");

    $dang_ky_id = $_GET['dang_ky_id'];

    $sql = "DELETE FROM dang_ky WHERE dang_ky_id = '$dang_ky_id'";

    if (mysqli_query($conn, $sql)){
        header("Location: list_register.php");
    } else {
        die("Lỗi xóa: " . mysqli_error($conn));
    }
