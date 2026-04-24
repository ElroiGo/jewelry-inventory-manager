<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: /jewelry_lamp/login.php');
    exit;
}
