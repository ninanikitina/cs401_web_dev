<?php

session_start();
require_once 'Dao.php';
$dao = new Dao();

# grab data from the $_POST
$email = $_POST['email'];
$pass = $_POST['pass'];
$pass_repeat = $_POST['pass_repeat'];

$users = $dao->getUsers();


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['message'][] = "Please enter a valid email";
}

$doesUserExist = False;
foreach ($users as $user) {
    if ($user["email"] == $email) {
        $doesUserExist = True;
        break;
    }
}

if ($doesUserExist) {
    $_SESSION['message'][] = "Email Address is Already Registered";
}


if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $pass)) {
    $_SESSION['message'][] = "Password must contain minimum eight characters, at least one letter and one number";
}

if ($pass != $pass_repeat) {
    $_SESSION['message'][] = "Passwords do NOT match.";
}


if (isset($_SESSION['message'])) {
    header('Location: sign_up.php');
    $_SESSION['sentiment'] = 'bad';
    $_SESSION['post'] = $_POST;
    exit;
}
$_SESSION['sentiment'] = 'good';
$_SESSION['message'][] = "Thank you for registration!";

unset($_SESSION['post']);


$dao->addNewUser($email, $pass);
$_SESSION['signed_user'] = $email;
header('Location: user_accaunt.php');
exit;
