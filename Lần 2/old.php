<?php
function old($field, $default = '')
{
    return isset($_POST[$field]) ? htmlspecialchars($_POST[$field]) : $default;
}
