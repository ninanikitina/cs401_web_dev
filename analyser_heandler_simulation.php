<?php

session_start();
require_once 'Dao.php';
$dao = new Dao();

# grab data from the $_POST
$x = $_POST['x'];
$y = $_POST['y'];
$z = $_POST['z'];
$nuc_channel = $_POST['nuc_channel'];
$actin_channel = $_POST['actin_channel'];
$users = $dao->getUsers();

# create regexp for resolutions
$xyz_pattern = "/^0\.\d{0,5}$/";

if (!preg_match($xyz_pattern, $x) or !preg_match($xyz_pattern, $y) or !preg_match($xyz_pattern, $z)) {
    $_SESSION['message'][] = "Resolution must be in micrometers: from 0.00001 to 0.99999 and no more than 5 decimals after the dot: ";
    if (!preg_match($xyz_pattern, $x)) {
        $_SESSION['message'][] = "- X resolution does not match this requirement ";
    }
    if (!preg_match($xyz_pattern, $y)) {
        $_SESSION['message'][] = "- Y resolution does not match this requirement ";
    }
    if (!preg_match($xyz_pattern, $z)) {
        $_SESSION['message'][] = "- Z resolution does not match this requirement ";
    }
}

if ($nuc_channel === $actin_channel) {
    $_SESSION['message'][] = "Actin and Nucleus channels can not be the same";
}

$imagePath = '';

if (count($_FILES) > 0) {
    if ($_FILES["myfile"]["error"] > 0) {
        $_SESSION['message'][] = "Please add file to analyse";
    } else {
        $basePath = "cells_img/";
        $imagePath = uniqid() . ".png";
        $original_filename = pathinfo($_FILES['myfile']['name'], PATHINFO_FILENAME);
        if (!move_uploaded_file($_FILES["myfile"]["tmp_name"], $basePath . $imagePath)) {
            throw new Exception("File move failed");
        }
    }
}


if (isset($_SESSION['message'])) {
    header('Location: analyse.php');
    $_SESSION['sentiment'] = 'bad';
    $_SESSION['post'] = $_POST;
    exit;
}
$_SESSION['sentiment'] = 'good';
$_SESSION['message'][] = "Your cells is processing now!";

unset($_SESSION['post']);

$email = $_SESSION['signed_user'];
$added_cell = $dao->addNewCell($x, $y, $z, $nuc_channel, $actin_channel, $imagePath, $email, $original_filename);
foreach ($added_cell as $cell) {
    $cell_id = $cell['cell_id'];
}

#Create simulations of actin statistics in csv file
$list = array(
    array("ID", "Actin Length", "Actin Xsection", "Actin Volume", "Number of fiber layers"),
    array(111, 20.8811, 0.552146314, 11.5294224, 356)
);
$baseStatPath = "stat/";
$statPath = uniqid() . ".csv";
$actin_stat_path = $baseStatPath . $statPath;
$file = fopen($actin_stat_path, "w");
foreach ($list as $line) {
    fputcsv($file, $line);
}
fclose($file);

#Create simulation of cell statistics
$nucleus_volume = rand(150, 5000);
$total_fiber_num = rand(50, 200);
$total_fiber_volume = rand(10, 100);
$total_fiber_length = $total_fiber_volume / 0.01;


$dao->addCellAnalytics($cell_id, $nucleus_volume, $total_fiber_num, $total_fiber_volume, $total_fiber_length, $actin_stat_path);
header('Location: user_accaunt.php');
exit;