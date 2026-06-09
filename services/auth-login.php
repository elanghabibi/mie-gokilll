<?php
if (isset($_SESSION['isLogin'])) {
    if ($_SESSION['role'] == 'admin') {
        header('location: '.$domain.'admin/dashboard');
        exit;
    } else if ($_SESSION['role'] == 'petugas') {
        header('location: '.$domain.'petugas/dashboard');
        exit;
    } else {
        header('location: '.$domain);
        exit;
    }
}
?>