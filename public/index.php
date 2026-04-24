<?php
session_start();

if (isset($_SESSION['admin_id'])) {
    header('Location: items_list.php');
    exit;
} else {
    header('Location: login.php');
    exit;
}
