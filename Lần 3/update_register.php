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

    // Lấy danh sách khóa học
    $ds_khoa_hoc = mysqli_query($conn, "SELECT id, ten_khoa_hoc FROM khoa_hoc");

    // Lấy thông tin đăng ký (đăng ký + học viên)
    $dang_ky_id = $_GET['id'];
    $sql = "
            SELECT 
                dk.*,
                hv.id, hv.ho_lot_hoc_vien, hv.ten_hoc_vien, hv.so_dien_thoai, hv.email, hv.cccd, hv.ngay_sinh 
            FROM dang_ky dk JOIN hoc_vien hv ON dk.hoc_vien_id = hv.id
            WHERE dk.id = $dang_ky_id; 
        ";
    $dang_ky = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($dang_ky);

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
            $hoc_vien_id = $row['hoc_vien_id'];
            $check_cccd = mysqli_query($conn, "SELECT * FROM hoc_vien WHERE cccd = '$cccd' AND id != $hoc_vien_id");
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
            $sua_hoc_vien = $conn->prepare("UPDATE hoc_vien SET ho_lot_hoc_vien=?, ten_hoc_vien=?, so_dien_thoai=?, email=?, cccd=?, ngay_sinh=? WHERE id=?");
            $sua_hoc_vien->bind_param("ssssssi", $ho_lot_hoc_vien, $ten_hoc_vien, $so_dien_thoai, $email, $cccd, $ngay_sinh, $row['hoc_vien_id']);
            $sua_hoc_vien->execute();

            // Thêm đăng ký
            $sua_dang_ky = $conn->prepare("UPDATE dang_ky SET so_tien_da_dong=?, khoa_hoc_id=?, hoc_vien_id=? WHERE id=?");
            $sua_dang_ky->bind_param("iiii", $so_tien_da_dong, $khoa_hoc_id, $hoc_vien_id, $dang_ky_id);
            if ($sua_dang_ky->execute()) {
                header("Location: list_register.php");
                exit();
            } else {
                $errors[] = "Lỗi thêm đăng ký" . $conn->error;
            }
        }
    }
    ?>
    <h2 align="center">CẬP NHẬT ĐĂNG KÝ</h2>
    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?= $e ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form action="" method="POST">
        <label for="">Họ lót học viên</label>
        <input type="text" name="ho_lot_hoc_vien" value="<?= $row['ho_lot_hoc_vien'] ?>"> <br>

        <label for="">Tên học viên</label>
        <input type="text" name="ten_hoc_vien" value="<?= $row['ten_hoc_vien'] ?>"> <br>

        <label for="">Số điện thoại học viên</label>
        <input type="text" name="so_dien_thoai" value="<?= $row['so_dien_thoai'] ?>"> <br>

        <label for="">Email</label>
        <input type="text" name="email" value="<?= $row['email'] ?>"> <br>

        <label for="">Căn cước công dân</label>
        <input type="text" name="cccd" value="<?= $row['cccd'] ?>"> <br>

        <label for="">Ngày sinh</label>
        <input type="date" name="ngay_sinh" value="<?= $row['ngay_sinh'] ?>"> <br>

        <label for="">Khóa học</label>
        <select name="khoa_hoc_id" id="">
            <option value="">-- Chọn khóa học --</option>
            <?php if ($ds_khoa_hoc->num_rows > 0) {
                while ($row_khoa_hoc = mysqli_fetch_assoc($ds_khoa_hoc)) {
                    $selected = ($row['khoa_hoc_id'] == $row_khoa_hoc['id'] ? 'selected' : '');
                    echo "<option value='{$row_khoa_hoc['id']}' $selected>{$row_khoa_hoc['ten_khoa_hoc']}</option>";
                }
            }
            ?>
        </select> <br>

        <label for="">Số tiền đã đóng</label>
        <input type="text" name="so_tien_da_dong" value="<?= $row['so_tien_da_dong'] ?>"> <br>

        <label for="">Ngày đăng ký</label>
        <input type="date" name="ngay_dang_ky" value="<?= $row['ngay_dang_ky'] ?>" readonly> <br>

        <input type="submit" value="Cập nhật">
    </form>
</body>

</html>