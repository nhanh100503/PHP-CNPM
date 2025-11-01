<?php
    require("connect.php");
    $ids = $_POST['selected_ids'] ?? [];

    foreach ($ids as $id) {
        mysqli_query($conn, "DELETE FROM dang_ky WHERE id = $id");
    }

    header("Location: list_register.php");
    exit();
