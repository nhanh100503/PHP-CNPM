<?php
    require("connect.php");

    $id = $_GET['id'];

    $sql = "DELETE FROM dang_ky WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: list_register.php");
        exit();
    } else {
        echo "Lỗi xóa: " . mysqli_error($conn);
    }
