<?php
error_reporting(E_ALL);

include "includes/connection.php";

$src = $_GET["src"];
$name = $_GET["tone"];
?>

<html>
<head>
	<title></title>
</head>
<body>

	<style>

	table {
  border-collapse: collapse;
  width:70%;
}

	table, th, td {
  border: 1px solid black;
}

td {
	padding:10px;
	width:40px;
}



</style>

<?php include "includes/navigation.php"; ?>
<br>
<br>

<table>

<?php



$sql="SELECT * FROM twitter_sentences_scores WHERE name = '" . $name . "' AND src = '" . $src . "'";
$rs=$mysqli->query($sql);
$rs->data_seek(0);
while($row = $rs->fetch_assoc()) {
	
	$name = ucfirst($row["name"]);
	$tid = $row["tid"];
	$score = $row["score"];
	$score = round((float)$score * 100) . '%';
	$text_str = $row["text_str"];
	$update_date = $row["update_date"];

	echo "<tr><td>" . $name . "</td>";
	echo "<td>" . $score . "</td>";
	echo "<td>" . $text_str . "</td>";
	echo "<td>" . $update_date . "</td></tr>";



}

?>

</table>
</body>
</html>
