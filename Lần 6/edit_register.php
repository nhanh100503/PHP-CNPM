<html>

<body>
    <?php
        require("connect.php");
        require("input.php");

        $ds_khoa_hoc = mysqli_query($conn, "SELECT khoa_hoc_id, ten_khoa_hoc FROM khoa_hoc");

        $dang_ky_id = $_GET['dang_ky_id'];
        $sql = "SELECT hv.*,
                       dk.*
                FROM dang_ky dk JOIN hoc_vien hv ON dk.hoc_vien_id = hv.hoc_vien_id
                WHERE dk.dang_ky_id = $dang_ky_id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] == "POST"){
            
        }
    ?>
</body>

</html>