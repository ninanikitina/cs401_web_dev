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
$_SESSION['message'][] = "Your cell has been processed now!";

unset($_SESSION['post']);

$email = $_SESSION['signed_user'];
$added_cell = $dao->addNewCell($x, $y, $z, $nuc_channel, $actin_channel, $imagePath, $email, $original_filename);
foreach ($added_cell as $cell) {
    $cell_id = $cell['cell_id'];
}

#Create simulation of cell statistics

$total_fiber_num = rand(50, 200);
$nucleus_volume = rand(150, 5000);
$total_fiber_volume = rand(3, 50);
$total_fiber_length = $total_fiber_volume / 0.07;


#Create simulations of actin statistics in csv file
$list = array(
    array("ID", "Actin Length", "Actin Xsection", "Actin Volume", "Number of fiber layers"),
);
$stack = array("orange", "banana");
array_push($stack, "apple", "raspberry");



for ($x = 0; $x < $total_fiber_num; $x++) {
    $stack = array(rand(50, 150), rand(5, 50) + rand(50, 1000)/1000, rand(50, 1000)/1000, rand(5, 80) + rand(50, 1000)/1000, rand(150, 300));
    array_push($list, $stack);
}


$baseStatPath = "stat/";
$statPath = uniqid() . ".csv";
$actin_stat_path = $baseStatPath . $statPath;
$file = fopen($actin_stat_path, "w");
foreach ($list as $line) {
    fputcsv($file, $line);
}
fclose($file);

$dao->addCellAnalytics($cell_id, $nucleus_volume, $total_fiber_num, $total_fiber_volume, $total_fiber_length, $actin_stat_path);
header('Location: user_accaunt.php');
exit;