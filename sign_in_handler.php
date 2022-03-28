<?php

session_start();
require_once 'Dao.php';
$dao = new Dao();

# grab data from the $_POST
$email = $_POST['email'];
$pass = $_POST['pass'];

$users = $dao->getUsers();


$validated_pass = Null;

$doesUserExist = False;
foreach ($users as $user) {
    if ($user["email"] == $email) {
        $doesUserExist = True;
        $validated_pass = $user["password"];
        break;
    }
}

if (!$doesUserExist) {
    $_SESSION['message'][] = "Account does not exist";
}

if ($pass != $validated_pass) {
    $_SESSION['message'][] = "Password is incorrect. Please try again";
}


if (isset($_SESSION['message'])) {
    header('Location: sign_in.php');
    $_SESSION['sentiment'] = 'bad';
    $_SESSION['post'] = $_POST;
    exit;
}

unset($_SESSION['post']);
$_SESSION['signed_user'] = $email;
header('Location: user_accaunt.php');
exit;

