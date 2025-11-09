<html>

<body>
    <?php
    session_start();
    require("connect.php");
    if (empty($_SESSION['tai_khoan'])) {
        header("Location: login.php");
        exit();
    }

    $sql = "SELECT
            CONCAT(hv.ho_lot_hoc_vien, ' ', hv.ten_hoc_vien) AS ho_ten,
            hv.so_dien_thoai, hv.email, hv.can_cuoc_cong_dan, hv.ngay_sinh,
            kh.ten_khoa_hoc, kh.hoc_phi,
            dk.dang_ky_id, dk.ngay_dang_ky, dk.so_tien_da_dong 
            FROM dang_ky dk JOIN hoc_vien hv ON dk.hoc_vien_id = hv.hoc_vien_id
                            JOIN khoa_hoc kh ON dk.khoa_hoc_id = kh.khoa_hoc_id
            ORDER BY dk.ngay_dang_ky DESC";
    $result = mysqli_query($conn, $sql);
    ?>
    <h2>DANH SÁCH ĐĂNG KÝ</h2>
    <form action="delete_selected.php" method="POST" onsubmit="return confirm('Chắc chắn xóa?')">
        <div>
            <button>
                <a href="add_register.php">Thêm</a>
            </button>
            <button type="submit">Xóa</button>
            <button>
                <a href="logout.php">Thoát</a>
            </button>
        </div>
        <table border="1">
            <tr>
                <th>STT</th>
                <th>Tên học viên</th>
                <th>Số điện thoại</th>
                <th>Email</th>
                <th>Căn cước công dân</th>
                <th>Ngày sinh</th>
                <th>Tên khóa học</th>
                <th>Học phí</th>
                <th>Số tiền đã đóng</th>
                <th>Ngày đăng ký</th>
                <th>Hành động</th>
                <th><input type="checkbox" id="selectAll"></th>
            </tr>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php $rows = mysqli_fetch_all($result, MYSQLI_ASSOC); $stt=1; ?>
                <?php foreach($rows as $row): ?>
                    <tr>
                        <td><?= $stt++ ?></td>
                        <td><?= $row['ho_ten'] ?></td>
                        <td><?= $row['so_dien_thoai'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['can_cuoc_cong_dan'] ?></td>
                        <td><?= date('d-m-Y', strtotime($row['ngay_sinh'])) ?></td>
                        <td><?= $row['ten_khoa_hoc'] ?></td>
                        <td><?= $row['hoc_phi'] ?></td>
                        <td><?= $row['so_tien_da_dong'] ?></td>
                        <td><?= date('d-m-Y', strtotime($row['ngay_dang_ky']))?></td>
                        <td>
                            <a href="edit_register.php?dang_ky_id=<?= $row['dang_ky_id'] ?>">Sửa</a>
                            <a href="delete_register.php?dang_ky_id=<?= $row['dang_ky_id'] ?>" onclick="return confirm('Chắc chắn xóa?')">Xóa</a>
                        </td>
                        <td><input type="checkbox" name="selected_ids[]" value="<?= $row['dang_ky_id'] ?>"></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="12"> Không có dữ liệu </td>
                </tr>
            <?php endif; ?>
        </table>
    </form>
    <script>
        const selectAll = document.getElementById('selectAll');
        selectAll.addEventListener('change', function(){
            const checkboxs = document.querySelectorAll('input[name="selected_ids[]"]');
            checkboxs.forEach(cb => cb.checked = this.checked);
        });
    </script>
</body>

</html>