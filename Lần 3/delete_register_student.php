<?php
    require("connect.php");
    
    // Lấy id đăng ký
    $id_dk = $_GET['id'];

    // Lấy id học viên
    $hoc_vien = "SELECT hoc_vien_id FROM dang_ky WHERE id = $id_dk";
    $result = mysqli_query($conn, $hoc_vien);
    $row = $result->fetch_assoc();
    $hoc_vien_id = $row['hoc_vien_id'];

    // Lệnh xóa đăng ký
    $xoa_dk = "DELETE FROM dang_ky WHERE id = $id_dk";
    // Lệnh xóa học viên
    $xoa_hv = "DELETE FROM hoc_vien WHERE id = $hoc_vien_id";

    if (mysqli_query($conn, $xoa_dk)) {
        if (mysqli_query($conn, $xoa_hv)) {
            header("Location: list_register.php");
            exit();
        }
    } else {
        echo "Lỗi xóa: " . mysqli_error($conn);
    }
