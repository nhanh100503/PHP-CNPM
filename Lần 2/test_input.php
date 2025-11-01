<?php
    function test_input($data){
        return htmlspecialchars(stripslashes(trim($data)));
    }
?>