<?php
    session_start();
    require("connect.php");

    $tai_khoan = $_POST['tai_khoan'];
    $mat_khau = md5($_POST['mat_khau']);

    $sql = "SELECT * FROM tai_khoan WHERE tai_khoan = '$tai_khoan' AND mat_khau = '$mat_khau'";
    $result = mysqli_query($conn, $sql);

    if ($result && $result->num_rows == 1){
        $row = mysqli_fetch_assoc($result);
        $_SESSION['tai_khoan'] = $row['tai_khoan'];
        header("Location: list_register.php");
        exit();
    } else {
        echo "Sai thông tin đăng nhập";
        header("Refresh:2, url=login.php");
        exit();
    }