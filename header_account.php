<?php
session_start();
$email = $_SESSION['signed_user'];
?>
<div class="nav-menu">
      <ul >
        <li class="hr_left">
          <a href="index.php">
            <img src="img/logo.png" alt="afilament logo" id="logo"></a>
        </li>
        <li class="hr_left">
          <a href="index.php">
            <img src="img/logo_text.png" alt="afilament logo" id="logo_text"></a>
        </li>
        <li class="hr_l">
          <a href="logout_heandler.php" class="button-main" id="button-sign-in">Log out</a>
        </li><li class="hr_l"><a href="user_accaunt.php" class ="li_a_hd" >My Analysis</a></li>
        <li class="hr_l"><a href="examples.php" class ="li_a_hd" >Analysis Examples</a></li>
        <li class="hr_l"><a href="image_requirements.php" class ="li_a_hd">Image Requirements</a></li>
      </ul>
</div>