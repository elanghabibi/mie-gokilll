<?php
session_start();
include 'services/domain.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_unset();
    session_destroy();

    header('location:'.$domain.'login.php');
    exit;
} else {
    header('location:'.$domain.'login.php');
    exit;
}

?>