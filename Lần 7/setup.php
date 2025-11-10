<?php
    $servername = "localhost:3307";
    $username = "root";
    $password = "";
    $dbname = "quany7";

    $conn = mysqli_connect($servername, $username, $password);

    if (mysqli_connect_error()){
        die("Lỗi kết nối: " . mysqli_error($conn));
    }

    mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $dbname");

    mysqli_select_db($conn, $dbname);

    $queries = [
        "CREATE TABLE IF NOT EXISTS tai_khoan(
            tai_khoan VARCHAR(50) PRIMARY KEY,
            mat_khau VARCHAR(50) NOT NULL
        )",

        "CREATE TABLE IF NOT EXISTS khoa_hoc(
            khoa_hoc_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            ma_khoa_hoc VARCHAR(50) NOT NULL,
            ten_khoa_hoc VARCHAR(50) NOT NULL,
            ngay_bat_dau DATE NOT NULL,
            ngay_ket_thuc DATE NOT NULL,
            hoc_phi FLOAT NOT NULL
        )",

        "CREATE TABLE IF NOT EXISTS hoc_vien(
            hoc_vien_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            ho_lot_hoc_vien VARCHAR(50) NOT NULL,
            ten_hoc_vien VARCHAR(50) NOT NULL,
            so_dien_thoai VARCHAR(50) NOT NULL,
            email VARCHAR(50) NOT NULL,
            can_cuoc_cong_dan VARCHAR(50) NOT NULL,
            ngay_sinh DATE NOT NULL    
        )",

        "CREATE TABLE IF NOT EXISTS dang_ky(
            dang_ky_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            so_tien_da_dong FLOAT NOT NULL,
            ngay_dang_ky DATE NOT NULL,
            hoc_vien_id INT(6) UNSIGNED,
            khoa_hoc_id INT(6) UNSIGNED,
            CONSTRAINT fk_hoc_vien FOREIGN KEY (hoc_vien_id) REFERENCES hoc_vien(hoc_vien_id),
            CONSTRAINT fk_khoa_hoc FOREIGN KEY (khoa_hoc_id) REFERENCES khoa_hoc(khoa_hoc_id)
        )"
    ];

    foreach ($queries as $query){
        if (mysqli_query($conn, $query)){
            echo "Tạo bảng thành công";
        } else {
            die("Lỗi tạo bảng" . mysqli_error($conn));
        }
    }

    $admin = "INSERT INTO tai_khoan VALUES ('admin', '".md5('admin')."')";
    mysqli_query($conn, $admin);

    $khoa_hoc = "INSERT INTO khoa_hoc (ma_khoa_hoc, ten_khoa_hoc, ngay_bat_dau, ngay_ket_thuc, hoc_phi) 
                 VALUES ('DI001', 'Lập trình PHP', '2025-09-01', '2025-12-31', 1000000),
                        ('DI002', 'Lập trình Python', '2025-09-01', '2025-12-31', 2000000),
                        ('DI003', 'Lập trình Java', '2025-09-01', '2025-12-31', 3000000)";

    mysqli_query($conn, $khoa_hoc);

    mysqli_close($conn);