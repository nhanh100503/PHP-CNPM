<html>

<body>
    <?php
        session_start();
        if(isset($_SESSION['tai_khoan'])){
            header("Location: list_register.php");
            exit();
        }
    ?>
    <h2>Đăng nhập</h2>
    <form action="login.php" method="POST">
        Tài khoản: <input type="text" name="tai_khoan"> <br>
        Mật khẩu: <input type="password" name="mat_khau"> <br>
        <input type="submit" value="Đăng nhập">
    </form>
</body>

</html>