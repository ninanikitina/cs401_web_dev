<?php
session_start();
$messages = array();
if (isset($_SESSION['message'])) {
    $messages = $_SESSION['message'];
    unset($_SESSION['message']);
}
if (!isset($_SESSION['signed_user'])) {
    header('Location: sign_in.php');
    exit;
}
require_once 'Dao.php';
$dao = new Dao();

function getUserInput ($lookup) {
    return (isset($_SESSION['post'][$lookup])) ? $_SESSION['post'][$lookup] : "";
}
?>

<!DOCTYPE html>
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
  <p>Follow these steps to run analysis</p>
  <p>step by step</p>


  <div class="container"><div class="form">
          <ol class = "errors">
              <?php
              foreach ($messages as $message) {
                  echo "<li><div class='message " . $_SESSION['sentiment']. "'>{$message}</div></li>";}
              $messages = Null ?>
          </ol>
          <form name="commentForm" action="analyser_heandler_simulation.php" method="POST" enctype="multipart/form-data">
              Nucleus channel
              <select name="nuc_channel">
                  <option>0</option>
                  <option>1</option>
                  <option>2</option>
                  <option selected="selected"><?php echo getUserInput('nuc_channel'); ?></option>
              </select>

              Actin channel
              <select name="actin_channel">
                  <option>0</option>
                  <option>1</option>
                  <option>2</option>
                  <option selected="selected"><?php echo getUserInput('nuc_channel'); ?></option>
              </select

              <label for="x_resolution">X resolution, µm per pixel</label>
              <input type="text" id="x_resolution" name="x" value="<?php echo getUserInput('x'); ?>" placeholder="...">
              <label for="y_resolution">Y resolution, µm per pixel</label>
              <input type="text" id="y_resolution" name="y" value="<?php echo getUserInput('y'); ?>" placeholder="...">
              <label for="z_resolution">Z resolution, µm per pixel</label>
              <input type="text" id="z_resolution" name="z" value="<?php echo getUserInput('z'); ?>" placeholder="...">
              <p>Upload an image (png, jpg):</p>
              <input class="button-square-green" type="file" id="myfile" name="myfile" />
              <input type="submit" value="Submit">
              <p>The program works in simulation mode. All statistics are randomly created to demonstrate forms functionality</p>
          </form>
  </div></div>

</div>
<?php require_once 'footer.php'; ?>



</body>
</html>