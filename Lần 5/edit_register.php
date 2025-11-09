<html>

<body>
    <?php
        require("connect.php");
        require("input.php");

        $ds_khoa_hoc = mysqli_query($conn, "SELECT khoa_hoc_id, ten_khoa_hoc FROM khoa_hoc");
        
        $dang_ky_id = $_GET['dang_ky_id'];
        $sql = "SELECT
                    dk.*,
                    hv.*
                FROM dang_ky dk JOIN hoc_vien hv ON dk.hoc_vien_id = hv.hoc_vien_id 
                WHERE dk.dang_ky_id = $dang_ky_id"; 
        $dang_ky = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($dang_ky);

        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $ho_lot_hoc_vien = input($_POST['ho_lot_hoc_vien']);
            $ten_hoc_vien = input($_POST['ten_hoc_vien']);
            $so_dien_thoai = input($_POST['so_dien_thoai']);
            $email = input($_POST['email']);
            $can_cuoc_cong_dan = input($_POST['can_cuoc_cong_dan']);
            $ngay_sinh = input($_POST['ngay_sinh']);

            $khoa_hoc_id = input($_POST['khoa_hoc_id']);
            $so_tien_da_dong = input($_POST['so_tien_da_dong']);

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
                $errors[] = "Email chưa đúng định dạng";
            if (empty($can_cuoc_cong_dan))
                $errors[] = "Chưa nhập căn cước công dân";
            elseif (!preg_match('/^[0-9]{12}$/', $can_cuoc_cong_dan))
                $errors[] = "Căn cước công dân chưa đúng định dạng";
            else {
                $hoc_vien_id = $row['hoc_vien_id'];
                $check_cccd = mysqli_query($conn, "SELECT * FROM hoc_vien WHERE can_cuoc_cong_dan = '$can_cuoc_cong_dan' AND hoc_vien_id != $hoc_vien_id");
                if ($check_cccd->num_rows > 0){
                    $errors[] = "Căn cước công dân đã tồn tại";
                }
            }
            if (empty($ngay_sinh))
                $errors[] = "Chưa nhập ngày sinh";
            if (empty($khoa_hoc_id))
                $errors[] = "Chưa học khóa học";
            if ($so_tien_da_dong == "" || !is_numeric($so_tien_da_dong))
                $errors[] = "Chưa nhập số tiền hoặc số tiền phải là số";
            elseif ($so_tien_da_dong <= 0)
                $errors[] = "Số tiền phải lớn hơn 0";

            if (empty($errors)){
                $cap_nhat_hoc_vien = mysqli_prepare($conn, "UPDATE hoc_vien 
                                                            SET ho_lot_hoc_vien=?, ten_hoc_vien=?, so_dien_thoai=?, email=?, can_cuoc_cong_dan=?, ngay_sinh=? 
                                                            WHERE hoc_vien_id = $hoc_vien_id");
                mysqli_stmt_bind_param($cap_nhat_hoc_vien, 'ssssss',$ho_lot_hoc_vien, $ten_hoc_vien, $so_dien_thoai, $email, $can_cuoc_cong_dan, $ngay_sinh);
                mysqli_stmt_execute($cap_nhat_hoc_vien);

                $cap_nhat_dang_ky = mysqli_prepare($conn, "UPDATE dang_ky
                                                           SET so_tien_da_dong=?, khoa_hoc_id=?
                                                           WHERE dang_ky_id = $dang_ky_id");
                mysqli_stmt_bind_param($cap_nhat_dang_ky, 'ii', $so_tien_da_dong, $khoa_hoc_id);
                if (mysqli_stmt_execute($cap_nhat_dang_ky)) {
                    header('Location: list_register.php');
                    exit();
                } else {
                    $errors[] = "Lỗi cập nhật " . mysqli_error($conn);
                }
            }
        }
    ?>
    <h2>CẬP NHẬT ĐĂNG KÝ</h2>
    <?php if (!empty($errors)): ?>
        <div>
            <ul>
                <?php foreach($errors as $e): ?>
                    <li><?= $e ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form action="" method="POST">
        Họ lót học viên: <input type="text" name="ho_lot_hoc_vien" value="<?= $row['ho_lot_hoc_vien'] ?>"> <br>
        Tên học viên: <input type="text" name="ten_hoc_vien" value="<?= $row['ten_hoc_vien'] ?>"> <br>
        Số điện thoại: <input type="text" name="so_dien_thoai" value="<?= $row['so_dien_thoai'] ?>"> <br>
        Email: <input type="text" name="email" value="<?= $row['email'] ?>"> <br>
        Căn cước công dân: <input type="text" name="can_cuoc_cong_dan" value="<?= $row['can_cuoc_cong_dan'] ?>"> <br>
        Ngày sinh: <input type="date" name="ngay_sinh" value="<?= $row['ngay_sinh'] ?>"> <br>
        Khóa học:
        <select name="khoa_hoc_id" id="">
            <option value="">-- Chọn khóa học --</option>
            <?php 
                if ($ds_khoa_hoc->num_rows > 0){
                    while($row_khoa_hoc = mysqli_fetch_assoc($ds_khoa_hoc)){
                        $selected = ($row['khoa_hoc_id'] == $row_khoa_hoc['khoa_hoc_id']) ? 'selected' : '';
                        echo "<option value='{$row_khoa_hoc['khoa_hoc_id']}' $selected>{$row_khoa_hoc['ten_khoa_hoc']}</option>";
                    }
                }
            ?>
        </select> <br>
        Số tiền đã đóng: <input type="number" name="so_tien_da_dong" value="<?= $row['so_tien_da_dong'] ?>"> <br>
        <input type="submit" value="Cập nhật">
    </form>
</body>

</html>