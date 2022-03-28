<?php
session_start();
if (isset($_SESSION['signed_user'])) {
    require_once 'header_account.php';
} else {require_once 'header_general.php';}
?>
