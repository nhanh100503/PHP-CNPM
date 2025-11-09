<html>

<body>
    <?php
    require("connect.php");
    require("input.php");
    require("old.php");

    $ds_khoa_hoc = mysqli_query($conn, "SELECT khoa_hoc_id, ten_khoa_hoc FROM khoa_hoc");

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $ho_lot_hoc_vien = input($_POST['ho_lot_hoc_vien']);
        $ten_hoc_vien = input($_POST['ten_hoc_vien']);
        $so_dien_thoai = input($_POST['so_dien_thoai']);
        $email = input($_POST['email']);
        $can_cuoc_cong_dan = input($_POST['can_cuoc_cong_dan']);
        $ngay_sinh = input($_POST['ngay_sinh']);
        $khoa_hoc_id = input($_POST['khoa_hoc_id']);
        $so_tien_da_dong = input($_POST['so_tien_da_dong']);
        $ngay_dang_ky = date('Y-m-d');

        if (empty($ho_lot_hoc_vien))
            $errors[] = "Chưa nhập họ lót học viên";
        if (empty($ten_hoc_vien))
            $errors[] = "Chưa nhập tên học viên";
        if (empty($so_dien_thoai))
            $errors[] = "Chưa nhập số điện thoại";
        elseif (!preg_match('/^[0-9]{10}$/', $so_dien_thoai))
            $errors[] = "Số điện thoại không đúng định dạng";
        if (empty($email))
            $errors[] = "Chưa nhập email";
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $errors[] = "Email không đúng định dạng";
        if (empty($can_cuoc_cong_dan))
            $errors[] = "Chưa nhập căn cước công dân";
        elseif (!preg_match('/^[0-9]{12}$/', $can_cuoc_cong_dan))
            $errors[] = "Căn cước không đúng định dạng";
        else {
            $check_cccd = mysqli_query($conn, "SELECT * FROM hoc_vien WHERE can_cuoc_cong_dan = '$can_cuoc_cong_dan'");
            if ($check_cccd->num_rows > 0) {
                $errors[] = "Căn cước đã được sử dụng";
            }
        }
        if (empty($ngay_sinh))
            $errors[] = "Bạn chưa nhập ngày sinh";
        if (empty($khoa_hoc_id))
            $errors[] = "Chưa chọn khóa học";
        if ($so_tien_da_dong == '' || !is_numeric($so_tien_da_dong))
            $errors[] = "Số tiền không được trống và phải là số";
        elseif ($so_tien_da_dong <= 0)
            $errors[] = "Số tiền phải lớn hơn 0";

        if (empty($errors)) {
            $them_hoc_vien = mysqli_prepare($conn, "INSERT INTO hoc_vien (ho_lot_hoc_vien, ten_hoc_vien, so_dien_thoai, email, can_cuoc_cong_dan, ngay_sinh) VALUES (?,?,?,?,?,?)");
            mysqli_stmt_bind_param($them_hoc_vien, 'ssssss', $ho_lot_hoc_vien, $ten_hoc_vien, $so_dien_thoai, $email, $can_cuoc_cong_dan, $ngay_sinh);
            if (mysqli_stmt_execute($them_hoc_vien)) {
                $hoc_vien_id = mysqli_insert_id($conn);

                $them_dang_ky = mysqli_prepare($conn, "INSERT INTO dang_ky (so_tien_da_dong, ngay_dang_ky, hoc_vien_id, khoa_hoc_id) VALUES (?,?,?,?)");
                mysqli_stmt_bind_param($them_dang_ky, 'isii', $so_tien_da_dong, $ngay_dang_ky, $hoc_vien_id, $khoa_hoc_id);
                if (mysqli_stmt_execute($them_dang_ky)) {
                    header("Location: list_register.php");
                    exit();
                } else {
                    $errors[] = "Lỗi thêm khóa học " . mysqli_error($conn);
                }
            } else {
                $errors[] = "Lỗi thêm học viên " . mysqli_error($conn);
            }
        }
    }
    ?>
    <h2>ĐĂNG KÝ KHÓA HỌC</h2>
    <?php if (!empty($errors)): ?>
        <div>
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li style="color:red"><?= $e ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form action="" method="POST">
        Họ lót học viên: <input type="text" name="ho_lot_hoc_vien" value="<?= old('ho_lot_hoc_vien') ?>"> <br>
        Tên học viên: <input type="text" name="ten_hoc_vien" value="<?= old('ten_hoc_vien') ?>"> <br>
        Số điện thoại: <input type="text" name="so_dien_thoai" value="<?= old('so_dien_thoai') ?>"> <br>
        Email: <input type="text" name="email" value="<?= old('email') ?>"> <br>
        Căn cước công dân: <input type="text" name="can_cuoc_cong_dan" value="<?= old('can_cuoc_cong_dan') ?>"> <br>
        Ngày sinh: <input type="date" name="ngay_sinh" value="<?= old('ngay_sinh') ?>"> <br>
        Khóa học:
        <select name="khoa_hoc_id" id="">
            <option value="">-- Chọn khóa học --</option>
            <?php
            if ($ds_khoa_hoc->num_rows > 0) {
                while ($rows = mysqli_fetch_assoc($ds_khoa_hoc)) {
                    $selected = (old('khoa_hoc_id') == $rows['khoa_hoc_id']) ? 'selected' : '';
                    echo "<option value='{$rows['khoa_hoc_id']}' $selected>{$rows['ten_khoa_hoc']}</option>";
                }
            }
            ?>
        </select> <br>
        Số tiền đã đóng: <input type="number" name="so_tien_da_dong" value="<?= old('so_tien_da_dong') ?>"> <br>
        <input type="submit" value="Đăng ký">
    </form>
</body>

</html>