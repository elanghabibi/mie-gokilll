<?php
if (!isset($_SESSION['isLogin']) || $_SESSION['role'] !== 'petugas') {
    header('Location: ' . $domain . 'login.php');
    exit;
}


?>