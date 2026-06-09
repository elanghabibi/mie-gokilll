<?php
if (!isset($_SESSION['isLogin']) || $_SESSION['role'] !== 'admin') {
    header('Location: ' . $domain . 'login.php');
    exit;
}


?>