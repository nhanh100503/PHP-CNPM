<?php 
    $servername = "localhost:3307";
    $username = "root";
    $password = "";
    $dbname = "quanly5";

    // Thiết lập kết nối
    $conn = mysqli_connect($servername, $username, $password);

    if (mysqli_connect_error()){
        die("Lỗi kết nối " . $conn->error);
    }

    // Tạo database
    mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $dbname");

    // Kết nối database
    mysqli_select_db($conn, $dbname);

    // Tạo bảng dữ liệu
    $queries = [
        "CREATE TABLE IF NOT EXISTS tai_khoan(
            tai_khoan VARCHAR(50) PRIMARY KEY,
            mat_khau VARCHAR(50) NOT NULL
        )",

        "CREATE TABLE IF NOT EXISTS khoa_hoc(
            khoa_hoc_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            ma_khoa_hoc VARCHAR(10) NOT NULL,
            ten_khoa_hoc VARCHAR(50) NOT NULL,
            hoc_phi FLOAT NOT NULL,
            ngay_bat_dau DATE NOT NULL,
            ngay_ket_thuc DATE NOT NULL    
        )",

        "CREATE TABLE IF NOT EXISTS hoc_vien(
            hoc_vien_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            ho_lot_hoc_vien VARCHAR(50) NOT NULL,
            ten_hoc_vien VARCHAR(50) NOT NULL,
            email VARCHAR(50) NOT NULL,
            so_dien_thoai VARCHAR(50) NOT NULL,
            can_cuoc_cong_dan VARCHAR(50) NOT NULL,
            ngay_sinh VARCHAR(50) NOT NULL
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
    if (mysqli_query($conn, $admin)){
        echo "Thêm admin thành công!";
    } else {
        die("Lỗi thêm admin " . mysqli_error($conn));
    }

    $khoa_hoc = "INSERT INTO khoa_hoc (ma_khoa_hoc, ten_khoa_hoc, hoc_phi, ngay_bat_dau, ngay_ket_thuc) 
                 VALUES ('DI001', 'Lập trình PHP', 2000000, '2025-09-20', '2025-12-30'),
                        ('DI002', 'Lập trình Java', 2000000, '2025-09-20', '2025-12-30'),
                        ('DI003', 'Lập trình Python', 2000000, '2025-09-20', '2025-12-30')";
    if (mysqli_query($conn, $khoa_hoc)){
        echo "Thêm khóa học thành công!";
    } else {
        die("Lỗi thêm khóa học " . mysqli_error($conn));
    }

    mysqli_close($conn);
?>