<?php
if (!isset($_SESSION['isLogin'])) {
    header('location: '.$domain.'login');
}
?>