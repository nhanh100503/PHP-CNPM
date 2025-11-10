<html>
<?php
session_start();
require("connect.php");

if (!empty($_SESSION['tai_khoan'])) {
    header("Location: list_register.php");
    exit();
}
?>

<body>
    <h2>ĐĂNG NHẬP</h2>
    <form action="log.php" method="POST">
        Tài khoản: <input type="text" name="tai_khoan"> <br>
        Mật khẩu: <input type="password" name="mat_khau"> <br>
        <input type="submit" value="Đăng nhập">
    </form>
</body>

</html>