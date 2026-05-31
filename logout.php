<?php
session_start();
include 'services/domain.php';
include 'services/auth-login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_unset();
    session_destroy();

    header('location:'.$domain.'login.php');
    exit;
} else {
    header('location:'.$domain.'admin/dashboard');
    exit;
}

?>