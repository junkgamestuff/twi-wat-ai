<?php
error_reporting(E_ALL);

include "includes/connection.php";

$update_date = date("Y-m-d H:i:s");
$tone_source = "twitter";


$sql="SELECT * FROM watson_keys";
$rs=$mysqli->query($sql);
$rs->data_seek(0);
while($row = $rs->fetch_assoc()) {
	$api_key = $row["api_key"];
}


$stmt = $mysqli->prepare("INSERT INTO update_date_time (update_date, source) VALUES (?,?)");
        $stmt -> bind_param('ss',$update_date,$tone_source);
        $stmt -> execute();	

$trend_id = $tid = mt_rand(111111111,999999999);

exec("/usr/local/bin/twurl '/1.1/trends/place.json?id=1' > " . __DIR__ . "/tweets-hashtags-trending/tweets-hashtags-trending.json");


$path = __DIR__ . "/tweets-hashtags-trending/tweets-hashtags-trending.json";
$file = file_get_contents($path);
$file = ltrim($file, "[");
$file = rtrim($file, "]");


$newfile = fopen(__DIR__ . "/tweets-hashtags-trending/tweets-hashtags-trending.json", "w") or die("Unable to open file!");
fwrite($newfile, $file);
fclose($newfile);

$path = __DIR__ . "/tweets-hashtags-trending/tweets-hashtags-trending.json";
$json_file = file_get_contents($path);
$json = json_decode($json_file);

foreach($json->trends as $trend) {

	$name = $trend->name;
	$url = $trend->url;
	$query = $trend->query;
	$promoted_content = $trend->promoted_content;
	$tweet_volume = $trend->tweet_volume;
	$src_id = str_replace("#","",$name);
	
		$stmt = $mysqli->prepare("INSERT INTO twitter_hashtag_trending (name,url,query,promoted_content,tweet_volume,src_id,update_date,trend_id) VALUES (?,?,?,?,?,?,?,?)");
                $stmt -> bind_param('sssiissi',$name,$url,$query,$promoted_content,$tweet_volume,$src_id,$update_date,$trend_id);
                $stmt -> execute();	

                $error = $mysqli->errno . ' ' . $mysqli->error;
}

$sql="SELECT * FROM twitter_hashtag_trending ORDER BY ID DESC LIMIT 1";
$rs=$mysqli->query($sql);
$rs->data_seek(0);
while($row = $rs->fetch_assoc()) {
	$update_date = $row["update_date"];

}


$src_id = array();
$sql="SELECT * FROM twitter_hashtag_trending WHERE trend_id = " . $trend_id . "";
$rs=$mysqli->query($sql);
$rs->data_seek(0);
while($row = $rs->fetch_assoc()) {
	$src_id[] = $row["src_id"];

}

$src_id = implode(",", $src_id);
$hashtags = $src_id;

$x = 0;
$sources = explode(",",$hashtags);
	foreach ($sources as $src) {


$number = $x++;
echo $src;

mysqli_query($mysqli,"DELETE FROM twitter_tone");

$type = "hashtag";

exec("/usr/local/bin/twurl '/1.1/search/tweets.json?q=" . $src . "&result_type=mixed&tweet_mode=extended&count=100' | /usr/local/bin/jq > " . __DIR__ . "/tweets-json/tweets-" . $number . "-" . $type . ".json");

$path = __DIR__ . "/tweets-json/tweets-" . $number . "-" . $type . ".json";
$json_file = file_get_contents($path);
$json = json_decode($json_file);

foreach($json->statuses as $status) {

	$text = $status->full_text;
	$text = preg_replace("/([0-9#][\x{20E3}])|[\x{00ae}\x{00a9}\x{203C}\x{2047}\x{2048}\x{2049}\x{3030}\x{303D}\x{2139}\x{2122}\x{3297}\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u", "", $text);
	$text = stripslashes($text);
	
	$id_str = $status->id_str;
	
	
		$stmt = $mysqli->prepare("INSERT INTO twitter_tone (text_str,id_str,update_date,src,type) VALUES (?,?,?,?,?)");
                $stmt -> bind_param('sisss',$text,$id_str,$update_date,$src,$type);
                $stmt -> execute();	

                $error = $mysqli->errno . ' ' . $mysqli->error;
}

$file = fopen(__DIR__ . "/tone-file/tone-" . $number . "-" . $type . ".json","w");
$text_str = array();
$sql="SELECT * FROM twitter_tone WHERE src = '" . $src . "' AND type = '" . $type . "'";
$rs=$mysqli->query($sql);
$rs->data_seek(0);
while($row = $rs->fetch_assoc()) { 
	$text_str[] = strip_tags($row["text_str"]);
			}

$text_str = implode(",", $text_str);
$text_str = str_replace(array("\n", "\r"), '', $text_str);
$text_str = str_replace("\"","",$text_str);

fwrite($file,"{");
fwrite($file,"\"text\":");
fwrite($file,"\"");
fwrite($file,$text_str);
fwrite($file,"\"");
fwrite($file,"}");

exec("curl -o " . __DIR__ . "/tone-output/tone-" . $number . "-" . $type . ".json -X POST -u 'apikey:" . $api_key . "' --header 'Content-Type: application/json' --data-binary @" . __DIR__ . "/tone-file/tone-" . $number . "-" . $type . ".json 'https://gateway.watsonplatform.net/tone-analyzer/api/v3/tone?version=2017-09-21'");

$path = __DIR__ ."/tone-output/tone-" . $number . "-" . $type . ".json";
$json_file = file_get_contents($path);
$json = json_decode($json_file);

	foreach ($json->document_tone->tones as $tones) {
		$score = $tones->score;
		$name = $tones->tone_name;

		$stmt = $mysqli->prepare("INSERT INTO twitter_tone_scores (score,name,update_date,src,type) VALUES (?,?,?,?,?)");
        $stmt -> bind_param('sssss',$score,$name,$update_date,$src,$type);
        $stmt -> execute();	

$sql="SELECT * FROM twitter_tone_scores WHERE src = '" . $src . "' AND type = '" . $type . "'";
$rs=$mysqli->query($sql);
$rs->data_seek(0);
while($row = $rs->fetch_assoc()) {
	$score = $row["score"];
	}

}

	foreach ($json->sentences_tone as $sentences_tones) {
		$text_str = $sentences_tones->text;
		$tid = mt_rand(111111111,999999999);


		$stmt = $mysqli->prepare("INSERT INTO twitter_sentences (text_str, update_date, tid, src, type) VALUES (?,?,?,?,?)");
        $stmt -> bind_param('ssiss',$text_str,$update_date,$tid,$src,$type);
        $stmt -> execute();	

		foreach ($sentences_tones->tones as $sub_tones) {

			$score = $sub_tones->score;
			$tone_id = $sub_tones->tone_id;

			$stmt = $mysqli->prepare("INSERT INTO twitter_sentences_scores (text_str, score, name, update_date, tid, src, type) VALUES (?,?,?,?,?,?,?)");
        	$stmt -> bind_param('sssssss',$text_str,$score, $tone_id,$update_date,$tid,$src,$type);
        	$stmt -> execute();	

		}
		

	}

}

?>
