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
<div id="banner">
  <ul>
    <li>
      <img src="img/cell.png" alt="cell" class = "cell" id="cell">
    </li>
    <li>
      <div class="container">
        <a href="analyse.php" class="button-main" id="button-quantify">Quntify Fibers</a>
      </button>
        </div>

    </li>
  </ul>
</div>



<?php
require_once 'Dao.php';
$dao = new Dao();
$cells = $dao->getCellAnalytics($_SESSION['signed_user']);

$messages = array();
if (isset($_SESSION['message'])) {
    $messages = $_SESSION['message'];
    unset($_SESSION['message']);
}
foreach ($messages as $message) {
    echo $message;
}

if ($cells->rowCount() == 0) {
    echo "<div id='sub_banner'><h2>You have not analyzed any fibers yet. To run your first analysis, please push Quntify Fibers.</h2></div>>";

}
else {
    echo "<div id='sub_banner'><h2>Previous analysis</h2></div>";
    echo "<div class='container_table'>";
    echo "<table>";
    echo "<tr>
        <th>Date</th>
        <th>Image Name</th>
        <th>Nucleus Volume, µm³</th>
        <th>Fibers Number, #</th>
        <th>Fibers Volume, µm³</th>
        <th>Fibers Length, µm</th>
        <th>Download Stat</th>
      </tr>";
    foreach ($cells as $cell) {
        echo "<tr>";
        echo "<td>" . $cell["created_at"] . "</td>";
        echo "<td>" . htmlspecialchars($cell["img_original_name"]) . "</td>";
        echo "<td>" . $cell["nucleus_volume"] . "</td>";
        echo "<td>" . $cell["total_fiber_num"] . "</td>";
        echo "<td>" . $cell["total_fiber_volume"] . "</td>";
        echo "<td>" . $cell["total_fiber_length"] . "</td>";
        echo "<td> <a href=" . $cell["actin_stat_path"] . " download'>Download</a></td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
}
?>
<?php require_once 'footer.php'; ?>

</body>
</html>