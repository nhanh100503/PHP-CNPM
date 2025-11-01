<?php
    $servername="localhost:3307";
    $username="root";
    $password="";
    $dbname="quanly3";

    // Kết nối MySQL
    $conn = new mysqli($servername, $username, $password);
    if($conn->connect_error){
        die("Lỗi kết nối". $conn->error);
    }

    // Tạo database
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    if (mysqli_query($conn, $sql) === TRUE){
        echo "Tạo '$dbname' thành công!";
    }

    // Kết nối database
    $conn->select_db($dbname);

    // Tạo bảng dữ liệu
    $queries = [
        "CREATE TABLE IF NOT EXISTS tai_khoan(
            tai_khoan VARCHAR(50) PRIMARY KEY,
            mat_khau VARCHAR(50) NOT NULL
        )",
        "CREATE TABLE IF NOT EXISTS khoa_hoc(
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            ma_khoa_hoc VARCHAR(10) NOT NULL,
            ten_khoa_hoc VARCHAR(50) NOT NULL,
            hoc_phi FLOAT NOT NULL,
            ngay_bat_dau DATE NOT NULL,
            ngay_ket_thuc DATE NOT NULL
        )",
        "CREATE TABLE IF NOT EXISTS hoc_vien(
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            ho_lot_hoc_vien VARCHAR(50) NOT NULL,
            ten_hoc_vien VARCHAR(50) NOT NULL,
            so_dien_thoai VARCHAR(10) NOT NULL,
            email VARCHAR(50) NOT NULL UNIQUE,
            cccd VARCHAR(12) NOT NULL UNIQUE,
            ngay_sinh DATE NOT NULL
        )",
        "CREATE TABLE IF NOT EXISTS dang_ky(
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            so_tien_da_dong FLOAT NOT NULL,
            ngay_dang_ky DATE NOT NULL,
            khoa_hoc_id INT(6) UNSIGNED,
            hoc_vien_id INT(6) UNSIGNED,
            CONSTRAINT fk_khoa_hoc FOREIGN KEY (khoa_hoc_id) REFERENCES khoa_hoc(id),
            CONSTRAINT fk_hoc_vien FOREIGN KEY (hoc_vien_id) REFERENCES hoc_vien(id)
        )"
    ];

    foreach($queries as $query){
        if(mysqli_query($conn, $query) === TRUE){
            echo "Tạo bảng thành công";
        } else {
            die("Lỗi tạo bảng: ". $conn->error);
        }
    }

    // Thêm tài khoản admin
    mysqli_query($conn, "INSERT INTO tai_khoan VALUES ('admin', '".md5('admin')."')");

    // Thêm dữ liệu khóa học
    $khoahoc = "INSERT INTO khoa_hoc (ma_khoa_hoc, ten_khoa_hoc, hoc_phi, ngay_bat_dau, ngay_ket_thuc) VALUES
                ('DI101', 'Lập trình PHP', 2500000, '2025-04-20', '2025-12-22'),
                ('DI102', 'Lập trình Java', 2500000, '2025-04-20', '2025-09-22'),
                ('DI103', 'Lập trình Web', 2500000, '2025-04-20', '2025-09-22');
                "; 
    if(mysqli_query($conn, $khoahoc) === TRUE){
        echo "Thêm dữ liệu bảng khóa học thành công";
    }
    
    //Thêm dữ liệu bảng học viên
    $hocvien = "INSERT INTO hoc_vien (ho_lot_hoc_vien, ten_hoc_vien, so_dien_thoai, email, cccd, ngay_sinh) VALUES
                ('Nguyễn Hoàng', 'Anh', '0343220769', 'anh@gmail.com', '089203011475', '2003-05-10'),
                ('Nguyễn Hoàng', 'Em', '0343220768', 'em@gmail.com','089203011477', '2003-04-10'),
                ('Nguyễn Hoàng', 'Cô', '0343220767', 'co@gmail.com','089203011476', '2003-05-12');
    ";
    if(mysqli_query($conn, $hocvien) === TRUE){
        echo "Thêm dữ liệu bảng học viên thành công";
    }

    $conn->close();
?>