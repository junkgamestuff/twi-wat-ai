<?php

error_reporting(E_ALL);

include "includes/connection.php";


$src = $_GET["src"];
$name = $_GET["name"];

//$src = "anime";
//$name = "Anger";

?>


<html>
<head>
	<title></title>
</head>
<body>


<?php


$score = array();
$sql="SELECT * FROM twitter_tone_scores WHERE name = '" . $name . "' AND src = '" . $src . "'";
$rs=$mysqli->query($sql);
$rs->data_seek(0);
while($row = $rs->fetch_assoc()) {
	
	$score[] = $row["score"];
}

count($score);
$score = array_filter($score);
$score = array_sum($score)/count($score);
$score = round((float)$score * 100) . '%';
?>


<?php echo $name; ?> - <?php echo $score; ?>


</body>
</html>

