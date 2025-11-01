<html>

<head>
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
            text-align: center;
            color: red;
        }

        ul {
            display: inline-block;
            text-align: left;
            margin: 0;
        }
    </style>
</head>

<body>
    <?php
    require("connect.php");
    require("input.php");
    require("old.php");

    // Lấy danh sách khóa học
    $ds_khoa_hoc = mysqli_query($conn, "SELECT id, ten_khoa_hoc FROM khoa_hoc");

    // Khai báo biến lỗi
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $ho_lot_hoc_vien = input($_POST['ho_lot_hoc_vien']);
        $ten_hoc_vien = input($_POST['ten_hoc_vien']);
        $so_dien_thoai = input($_POST['so_dien_thoai']);
        $email = input($_POST['email']);
        $cccd = input($_POST['cccd']);
        $ngay_sinh = input($_POST['ngay_sinh']);

        $khoa_hoc_id = input($_POST['khoa_hoc_id']);
        $so_tien_da_dong = input($_POST['so_tien_da_dong']);
        $ngay_dang_ky = date('Y-m-d');

        // Validation
        if (empty($ho_lot_hoc_vien))
            $errors[] = "Chưa nhập họ lót học viên";
        if (empty($ten_hoc_vien))
            $errors[] = "Chưa nhập tên học viên";

        if (empty($so_dien_thoai))
            $errors[] = "Chưa nhập số điện thoại học viên";
        elseif (!preg_match('/^[0-9]{10}$/', $so_dien_thoai))
            $errors[] = "Số điện thoại chưa đúng định dạng";

        if (empty($email))
            $errors[] = "Chưa nhập email học viên";
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $errors[] = "Email không đúng định dạng";

        if (empty($cccd))
            $errors[] = "Bạn chưa nhập căn cước công dân";
        elseif (!preg_match('/^[0-9]{12}$/', $cccd))
            $errors[] = "Căn cước phải có đủ 12 số";
        else {
            $check_cccd = mysqli_query($conn, "SELECT * FROM hoc_vien WHERE cccd = '$cccd'");
            if ($check_cccd->num_rows > 0) {
                $errors[] = "Căn cước công dân đã được đăng ký";
            }
        }
        if (empty($ngay_sinh))
            $errors[] = "Bạn chưa nhập ngày sinh";
        if (empty($khoa_hoc_id))
            $errors[] = "Bạn chưa chọn khóa học";
        if ($so_tien_da_dong === "" || !is_numeric($so_tien_da_dong))
            $errors[] = "Số tiền không được để trống";
        elseif ($so_tien_da_dong <= 0)
            $errors[] = "Số tiền phải lớn hơn 0";

        if (empty($errors)) {

            // Thêm học viên
            $them_hoc_vien = $conn->prepare("INSERT INTO hoc_vien (ho_lot_hoc_vien, ten_hoc_vien, so_dien_thoai, email, cccd, ngay_sinh) VALUES (?,?,?,?,?,?)");
            $them_hoc_vien->bind_param("ssssss", $ho_lot_hoc_vien, $ten_hoc_vien, $so_dien_thoai, $email, $cccd, $ngay_sinh);
            if ($them_hoc_vien->execute()) {
                // Lấy id học viên
                $hoc_vien_id = $conn->insert_id;

                // Thêm đăng ký
                $them_dang_ky = $conn->prepare("INSERT INTO dang_ky (so_tien_da_dong, ngay_dang_ky, khoa_hoc_id, hoc_vien_id) VALUES (?,?,?,?)");
                $them_dang_ky->bind_param("isii", $so_tien_da_dong, $ngay_dang_ky, $khoa_hoc_id, $hoc_vien_id);
                if ($them_dang_ky->execute()) {
                    header("Location: list_register.php");
                    exit();
                } else {
                    $errors[] = "Lỗi thêm đăng ký" . $conn->error;
                }
            } else {
                $errors[] = "Lỗi thêm học viên" . $conn->error;
            }
        }
    }
    ?>
    <h2 align="center">Đăng ký khóa học</h2>
    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li> <?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form action="" method="POST">
        <label for="">Họ lót học viên</label>
        <input type="text" name="ho_lot_hoc_vien" value="<?= old('ho_lot_hoc_vien') ?>"> <br>

        <label for="">Tên học viên</label>
        <input type="text" name="ten_hoc_vien" value="<?= old('ten_hoc_vien') ?>"> <br>

        <label for="">Số điện thoại</label>
        <input type="text" name="so_dien_thoai" value="<?= old('so_dien_thoai') ?>"> <br>

        <label for="">Email học viên</label>
        <input type="text" name="email" value="<?= old('email') ?>"> <br>

        <label for="">Căn cước công dân</label>
        <input type="text" name="cccd" value="<?= old('cccd') ?>"> <br>

        <label for="">Ngày sinh</label>
        <input type="date" name="ngay_sinh" value="<?= old('ngay_sinh') ?>"> <br>

        <label for="">Khóa học</label>
        <select name="khoa_hoc_id">
            <option value="">-- Chọn khóa học --</option>
            <?php
            if ($ds_khoa_hoc->num_rows > 0) {
                while ($row = $ds_khoa_hoc->fetch_assoc()) {
                    $selected = (old('khoa_hoc_id') == $row['id']) ? 'selected' : '';
                    echo "<option value='{$row['id']}' $selected>{$row['ten_khoa_hoc']}</option>";
                }
            }
            ?>
        </select> <br>

        <label for="">Số tiền đóng</label>
        <input type="number" name="so_tien_da_dong" value="<?= old('so_tien_da_dong') ?>"> <br>

        <input type="submit" value="Đăng ký"> <br>
    </form>
</body>

</html>