<?php
session_start();
$messages = array();
if (isset($_SESSION['message'])) {
    $messages = $_SESSION['message'];
    unset($_SESSION['message']);
}

require_once 'Dao.php';
$dao = new Dao();

function getUserInput ($lookup) {
    return (isset($_SESSION['post'][$lookup])) ? $_SESSION['post'][$lookup] : "";
}
?>

<html lang="en">
<head>
    <title>afilament</title>
    <link rel="shortcut icon" href="img/logo.png">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="my_style_sheet.css">

</head>
<?php require_once 'header.php'; ?>
<body>


<div class="analyse_banner">
</div>

<div class="container" ><div class="form">
        <ol class = "errors">
            <?php
            foreach ($messages as $message) {
                echo "<li><div class='message " . $_SESSION['sentiment']. "'>{$message}</div></li>";}
            $messages = Null ?>
        </ol>


        <h1>Sign Up</h1>
        <p>Please fill in this form to create an account.</p>
        <hr>

        <form name="signUpForm" action="sign_up_handler.php" method="POST" enctype="multipart/form-data">
            <label for="email">Email</label>
            <input type="text" name="email" value="<?php echo getUserInput('email'); ?>" placeholder="Enter Email">

            <label for="pass">Password</label>
            <input type="text" name="pass" value="<?php echo getUserInput('pass'); ?>" placeholder="Enter Password">

            <label for="pass_repeat">Repeat Password</label>
            <input type="text" name="pass_repeat" value="<?php echo getUserInput('pass_repeat'); ?>" placeholder="Repeat Password">

            <input type="submit" value="Submit">
        </form>
    </div></div>

<?php require_once 'footer.php'; ?>
</body>
</html>
