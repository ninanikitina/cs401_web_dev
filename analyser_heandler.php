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
        $imagePath = uniqid() . ".lif";
        $original_filename = pathinfo($_FILES['myfile']['name'], PATHINFO_FILENAME);
        if (!move_uploaded_file($_FILES["myfile"]["tmp_name"], $basePath . $imagePath)) {
            throw new Exception("File move failed");
        }
    }
}
$confocal_img = "D:/University/Classes/CS401_Intro-to-web-dev/web_site/" . $basePath . $imagePath;


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

#Create actin stat path
$baseStatPath = "D:/University/Classes/CS401_Intro-to-web-dev/web_site/stat/";
$statPath = uniqid() . ".csv";
$actin_stat_path = $baseStatPath . $statPath;




#add this cell data to the cell table:
#call pyton program that returns the data:
$arguments= $confocal_img." ".$x." ".$y." ".$z." ".$nuc_channel." ".$actin_channel." ".$actin_stat_path;
//$command = escapeshellcmd("C:\Users\nnina\miniconda3\condabin\activate.bat pytorch-conda-env && python D:\BioLab\src\test_script_for_web_site.py '$input'");
$output = shell_exec('C:\Users\nnina\miniconda3\condabin\activate.bat pytorch-conda-env && python D:\BioLab\src\test_script_for_web_site.py ".$arguments"');
$_SESSION['message'][] = $output;

//$dao->addCellAnalytics($cell_id, $nucleus_volume, $total_fiber_num, $total_fiber_volume, $total_fiber_length, $actin_stat_path);
header('Location: user_accaunt.php');
exit;