<?php
    require("connect.php");

    $ids = $_POST['selected_ids'] ?? [];

    foreach ($ids as $id) {
        $result = mysqli_query($conn, "SELECT hoc_vien_id FROM dang_ky WHERE id = $id");
        if ($result && $result->num_rows > 0) {
            $row = mysqli_fetch_assoc($result);
            $hoc_vien_id = $row['hoc_vien_id'];
            
            mysqli_query($conn, "DELETE FROM dang_ky WHERE id = $id");
            mysqli_query($conn, "DELETE FROM hoc_vien WHERE id = $hoc_vien_id");
        }
    }

    header("Location: list_register.php");
    exit();
?>
