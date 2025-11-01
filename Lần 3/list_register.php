<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h2 {
            text-align: center;
        }

        .actions {
            text-align: center;
            margin: 20px;
        }

        .actions a,
        .actions button {
            text-decoration: none;
            padding: 8px 14px;
            background-color: #4CAF50;
            color: white;
            border-radius: 4px;
            margin-right: 10px;
            border: none;
            cursor: pointer;
        }

        .actions .delete-selected {
            background-color: #f44336;
        }

        table {
            border-collapse: collapse;
            width: 90%;
            margin: auto;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            text-align: center;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        .btn {
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            color: white;
        }

        .btn.edit {
            background-color: #2196F3;
        }

        .btn.delete {
            background-color: #f44336;
        }

        input[type="checkbox"] {
            transform: scale(1.3);
        }
    </style>
</head>

<body>
    <?php
    session_start();
    require("connect.php");

    // Kiểm tra người dùng có đăng nhập hay chưa?
    // Nếu chưa thì chuyển về trang đăng nhập
    if (empty($_SESSION['tai_khoan'])) {
        header("Location: login_page.php");
        exit();
    }

    // Lấy danh sách đăng ký
    $sql = "
                SELECT
                concat(hv.ho_lot_hoc_vien, ' ', hv.ten_hoc_vien) AS ho_ten,
                hv.email,
                hv.cccd,
                hv.so_dien_thoai,
                kh.ma_khoa_hoc,
                kh.ten_khoa_hoc,
                kh.hoc_phi,
                dk.id,
                dk.so_tien_da_dong,
                dk.ngay_dang_ky
                FROM dang_ky dk
                     JOIN hoc_vien hv ON dk.hoc_vien_id = hv.id
                     JOIN khoa_hoc kh ON dk.khoa_hoc_id = kh.id
                ORDER BY dk.ngay_dang_ky DESC
            ";
    $result = mysqli_query($conn, $sql);
    ?>
    <h2>DANH SÁCH ĐĂNG KÝ KHÓA HỌC</h2>

    <form action="delete_selected_student.php" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
        <div class="actions">
            <button>
                <a href="add_register.php">Thêm đăng ký</a>
            </button>
            <button type="submit">Xóa</button>
            <button style="background-color:red">
                <a  style="background-color:red"  href="logout.php">Thoát</a>
            </button>
        </div>

        <table border="1">
            <tr>
                <th>STT</th>
                <th>Tên học viên</th>
                <th>Email</th>
                <th>Căn cước</th>
                <th>Số điện thoại</th>
                <th>Tên khóa học</th>
                <th>Học phí</th>
                <th>Số tiền đã đóng</th>
                <th>Ngày đăng ký</th>
                <th>Hành động</th>
                <th><input type="checkbox" id="selectAll"></th>
            </tr>

            <?php if ($result && $result->num_rows > 0): ?>
                <?php $rows = $result->fetch_all(MYSQLI_ASSOC);
                $stt = 1; ?>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= $stt++ ?></td>
                        <td><?= $row['ho_ten'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['cccd'] ?></td>
                        <td><?= $row['so_dien_thoai'] ?></td>
                        <td><?= $row['ten_khoa_hoc'] ?></td>
                        <td><?= $row['hoc_phi'] ?></td>
                        <td><?= $row['so_tien_da_dong'] ?></td>
                        <td><?= date('d-m-Y', strtotime($row['ngay_dang_ky'])) ?></td>
                        <td>
                            <a href="update_register.php?id=<?= $row['id'] ?>">Sửa</a>
                            <a href="delete_register.php?id=<?= $row['id'] ?>" onclick="return confirm('Xóa dòng này?')">Xóa</a>
                        </td>
                        <td><input type="checkbox" name="selected_ids[]" value="<?= $row['id'] ?>"></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" style="text-align:center;">Chưa có học viên đăng ký</td>
                </tr>
            <?php endif; ?>
        </table>
    </form>


    <script>
        // Chức năng chọn tất cả
        const selectAll = document.getElementById('selectAll');
        selectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
</body>

</html>