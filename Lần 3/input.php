<?php
    function input($data){
        return htmlspecialchars(stripslashes(trim($data)));
    }
?>