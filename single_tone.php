<?php 
//error_reporting(E_ALL);

include "includes/connection.php";

$sql="SELECT * FROM watson_keys";
$rs=$mysqli->query($sql);
$rs->data_seek(0);
while($row = $rs->fetch_assoc()) {
	$api_key = $row["api_key"];
}


$tone = urlencode($_POST["tone"]);
$action = $_POST["action"];

if ($action == "post_tone") {

exec("curl -o " . __DIR__ . "/single-tone-output/single-tone.json -X GET -u 'apikey:" . $api_key . "' 'https://gateway.watsonplatform.net/tone-analyzer/api/v3/tone?version=2017-09-21&text=" . $tone . "'");
}

?>
<html>
<head>
<title></title>
</head>
<body>
<form action = "<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<input type = "hidden" name="action" value="post_tone">
<textarea rows="20" cols="80" name="tone">
</textarea>
<br>
<br>
<input type = "submit" value="Post">
</form>
<?php 
if ($action == "post_tone") {
	$path = __DIR__ ."/single-tone-output/single-tone.json";
	$json_file = file_get_contents($path);
	$json = json_decode($json_file);

		foreach ($json->document_tone->tones as $tones) {
			echo $score = $tones->score . " - ";
			echo $name = $tones->tone_name;
			echo "<br>";
		}
		echo "<br>";
		echo "<br>";

		foreach ($json->sentences_tone as $sentences_tones) {
			echo $text_str = $sentences_tones->text . " - ";
			foreach ($sentences_tones->tones as $sub_tones) {
				echo $score = $sub_tones->score . " - ";
				echo $tone_id = $sub_tones->tone_id;
				echo "<br>";
			}
		}
}

?>

</body>
</html>