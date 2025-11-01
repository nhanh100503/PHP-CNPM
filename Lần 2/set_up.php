<?php
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "quanly";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}
echo "Kết nối MySQL thành công!<br>";

$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database '$dbname' tạo mới thành công!<br>";
} else {
    die("Lỗi tạo database: " . $conn->error);
}

$conn->select_db($dbname);

$queries = [
    "CREATE TABLE IF NOT EXISTS tai_khoan(
        tai_khoan VARCHAR(50) PRIMARY KEY,
        mat_khau VARCHAR(255) NOT NULL
    )",

    "CREATE TABLE IF NOT EXISTS khoa_hoc(
        khoa_hoc_id INT(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
        ma_khoa_hoc INT(11) NOT NULL,
        ten_khoa_hoc VARCHAR(50) NOT NULL,
        hoc_phi INT(11) NOT NULL
    )",

    "CREATE TABLE IF NOT EXISTS hoc_vien(
        hoc_vien_id INT(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
        ten_hoc_vien VARCHAR(50) NOT NULL,
        ho_lot_hoc_vien VARCHAR(50) NOT NULL,
        so_dien_thoai VARCHAR(10) NOT NULL,
        email VARCHAR(50) UNIQUE NOT NULL,
        cccd VARCHAR(20) UNIQUE NOT NULL,
        ngay_sinh DATE NOT NULL
    )",

    "CREATE TABLE IF NOT EXISTS dang_ky(
        id INT(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
        so_tien_da_dong INT(11) NOT NULL,
        ngay_dang_ky DATE NOT NULL,
        khoa_hoc_id INT(11) UNSIGNED NOT NULL,
        hoc_vien_id INT(11) UNSIGNED NOT NULL,
        CONSTRAINT fk_khoa_hoc FOREIGN KEY (khoa_hoc_id) REFERENCES khoa_hoc(khoa_hoc_id),
        CONSTRAINT fk_hoc_vien FOREIGN KEY (hoc_vien_id) REFERENCES hoc_vien(hoc_vien_id)
    )"
];

foreach ($queries as $query) {
    if ($conn->query($query) === TRUE) {
        echo "Tạo bảng thành công!<br>";
    } else {
        die("Lỗi tạo bảng: " . $conn->error);
    }
}

$conn->query("INSERT INTO tai_khoan (tai_khoan, mat_khau) VALUES ('admin', '" . md5('admin') . "')");
echo "Thêm tài khoản admin mặc định thành công!<br>";

$insertCourses = "
    INSERT INTO khoa_hoc (ma_khoa_hoc, ten_khoa_hoc, hoc_phi) VALUES
    (101, 'Lập trình Java cơ bản', 1500000),
    (102, 'Phát triển Web với Spring Boot', 2500000),
    (103, 'Phân tích dữ liệu với Python', 2000000);
";

if ($conn->query($insertCourses) === TRUE) {
    echo "Thêm 3 khóa học mẫu thành công!<br>";
} else {
    echo "Lỗi thêm dữ liệu: " . $conn->error . "<br>";
}

$insertStudents = "
    INSERT INTO hoc_vien (ten_hoc_vien, ho_lot_hoc_vien, so_dien_thoai, email, cccd, ngay_sinh) VALUES
    ('An', 'Nguyễn Văn', '0905123456', 'an.nguyen@example.com', '012345678901', '2000-03-12'),
    ('Bình', 'Trần Thị', '0906234567', 'binh.tran@example.com', '123456789012', '1999-08-25'),
    ('Cường', 'Lê Hoàng', '0907345678', 'cuong.le@example.com', '234567890123', '2001-01-10');
";

if ($conn->query($insertStudents) === TRUE) {
    echo "Thêm 3 học viên mẫu thành công!<br>";
} else {
    echo "Lỗi thêm dữ liệu học viên: " . $conn->error . "<br>";
}


$conn->close();
echo "Hoàn tất setup database!";
