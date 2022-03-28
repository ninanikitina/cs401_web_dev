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

        <h1>Sign In</h1>
        <p>Please sign in so we can continue.</p>
        <hr>

        <form name="signInForm" action="sign_in_handler.php" method="POST" enctype="multipart/form-data">
            <label for="email">Email</label>
            <input type="text" name="email" placeholder="Enter Email" value="<?php echo getUserInput('email'); ?>">
            <label for="pass">Password</label>
            <input type="text" name="pass" placeholder="Enter Password" value="<?php echo getUserInput('pass'); ?>">
            <input type="submit" value="Submit">
        </form>
    </div></div>

<?php require_once 'footer.php'; ?>
</body>
</html>

