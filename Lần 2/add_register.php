<?php
require("connect.php");
require("old.php");
require("test_input.php");


// Lấy danh sách khóa học để hiển thị combobox
$khoa_hoc_result = $conn->query("SELECT khoa_hoc_id, ten_khoa_hoc FROM khoa_hoc");

// Khai báo biến lỗi và giá trị
$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ten_hoc_vien = test_input($_POST["ten_hoc_vien"]);
    $ho_lot_hoc_vien = test_input($_POST["ho_lot_hoc_vien"]);
    $so_dien_thoai = test_input($_POST["so_dien_thoai"]);
    $email = test_input($_POST["email"]);
    $cccd = test_input($_POST["cccd"]);
    $ngay_sinh = test_input($_POST["ngay_sinh"]);
    $khoa_hoc_id = test_input($_POST["khoa_hoc_id"]);
    $so_tien_da_dong = test_input($_POST["so_tien_da_dong"]);
    // $ngay_dang_ky = test_input($_POST["ngay_dang_ky"]);
    $ngay_dang_ky = date("Y-m-d");



    // ========== VALIDATION ==========
    if (empty($ten_hoc_vien)) $errors[] = "Tên học viên không được để trống.";
    if (empty($ho_lot_hoc_vien)) $errors[] = "Họ lót học viên không được để trống.";
    if (empty($so_dien_thoai)) $errors[] = "Số điện thoại không được để trống.";
    elseif (!preg_match('/^[0-9]{10}$/', $so_dien_thoai)) $errors[] = "Số điện thoại phải đủ 10 chữ số.";

    if (empty($email)) $errors[] = "Email không được để trống.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email không hợp lệ.";

    if (empty($cccd)) $errors[] = "Căn cước không được để trống.";
    elseif (!preg_match('/^[0-9]{12}$/', $cccd)) $errors[] = "Căn cước phải là số từ 9 đến 12 chữ số.";
    else {
        // Kiểm tra trùng CCCD trong DB
        $check_cccd = $conn->prepare("SELECT * FROM hoc_vien WHERE cccd = ?");
        $check_cccd->bind_param("s", $cccd);
        $check_cccd->execute();
        $result = $check_cccd->get_result();
        if ($result->num_rows > 0) $errors[] = "Căn cước này đã được đăng ký."; 
    }

    if (empty($ngay_sinh)) $errors[] = "Ngày sinh không được để trống.";
    if (empty($khoa_hoc_id)) $errors[] = "Vui lòng chọn khóa học.";
    if ($so_tien_da_dong === "" || !is_numeric($so_tien_da_dong)) {
        $errors[] = "Số tiền phải là số và không được để trống.";
    } elseif ($so_tien_da_dong <= 0) {
        $errors[] = "Số tiền phải lớn hơn 0.";
    }

    // if (empty($ngay_dang_ky)) $errors[] = "Ngày đăng ký không được để trống.";
    // elseif (strtotime($ngay_dang_ky) < strtotime(date("Y-m-d"))) $errors[] = "Ngày đăng ký phải từ hôm nay trở đi.";

    // ========== INSERT ==========
    if (empty($errors)) {
        // 1️⃣ Thêm học viên
        $stmt1 = $conn->prepare("INSERT INTO hoc_vien (ten_hoc_vien, ho_lot_hoc_vien, so_dien_thoai, email, cccd, ngay_sinh) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt1->bind_param("ssssss", $ten_hoc_vien, $ho_lot_hoc_vien, $so_dien_thoai, $email, $cccd, $ngay_sinh);
        if ($stmt1->execute()) {
            $hoc_vien_id = $conn->insert_id;

            // 2️⃣ Thêm đăng ký khóa học
            $stmt2 = $conn->prepare("INSERT INTO dang_ky (so_tien_da_dong, ngay_dang_ky, khoa_hoc_id, hoc_vien_id) VALUES (?, ?, ?, ?)");
            $stmt2->bind_param("isii", $so_tien_da_dong, $ngay_dang_ky, $khoa_hoc_id, $hoc_vien_id);
            if ($stmt2->execute()) {
                header("Refresh:2; url: list_register.php");
                exit();
            } else {
                $errors[] = "Lỗi khi thêm đăng ký: " . $conn->error;
            }
        } else {
            $errors[] = "Lỗi khi thêm học viên: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng ký khóa học</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f4f4f4;
            margin: 40px;
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            margin: auto;
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>

<body>

    <h2 align="center">Đăng ký khóa học</h2>

    <?php
    if (!empty($errors)) {
        echo "<div class='error'><ul>";
        foreach ($errors as $e) echo "<li>$e</li>";
        echo "</ul></div>";
    }
    ?>

    <form method="post">
        <label>Họ lót học viên:</label>
        <input type="text" name="ho_lot_hoc_vien" value="<?= old('ho_lot_hoc_vien') ?>">

        <label>Tên học viên:</label>
        <input type="text" name="ten_hoc_vien" value="<?= old('ten_hoc_vien') ?>">

        <label>Số điện thoại:</label>
        <input type="text" name="so_dien_thoai" value="<?= old('so_dien_thoai') ?>">

        <label>Email:</label>
        <input type="text" name="email" value="<?= old('email') ?>">

        <label>CCCD:</label>
        <input type="text" name="cccd" value="<?= old('cccd') ?>">

        <label>Ngày sinh:</label>
        <input type="date" name="ngay_sinh" value="<?= old('ngay_sinh') ?>">

        <label>Khóa học:</label>
        <select name="khoa_hoc_id">
            <option value="">-- Chọn khóa học --</option>
            <?php
            if ($khoa_hoc_result->num_rows > 0) {
                while ($row = $khoa_hoc_result->fetch_assoc()) {
                    $selected = (old('khoa_hoc_id') == $row['khoa_hoc_id']) ? 'selected' : '';
                    echo "<option value='{$row['khoa_hoc_id']}' $selected>{$row['ten_khoa_hoc']}</option>";
                }
            }
            ?>
        </select>

        <label>Số tiền đã đóng:</label>
        <input type="number" name="so_tien_da_dong" min="0" value="<?= old('so_tien_da_dong') ?>">

        <!-- <label>Ngày đăng ký:</label>
        <input type="date" name="ngay_dang_ky" value="<?= old('ngay_dang_ky') ?>"> -->
        <label>Ngày đăng ký:</label>
        <input type="text" value="<?= date('Y-m-d') ?>" readonly>

        <input type="submit" value="Đăng ký">
    </form>


</body>

</html>