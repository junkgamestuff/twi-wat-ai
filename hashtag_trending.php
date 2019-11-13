<?php

error_reporting(E_ALL);

include "includes/connection.php";

$update_date = date("Y-m-d H:i:s");


exec("/usr/local/bin/twurl '/1.1/trends/place.json?id=1' > tweets-hashtags-trending/tweets-hashtags-trending.json");


$path = "tweets-hashtags-trending/tweets-hashtags-trending.json";
$file = file_get_contents($path);
$file = ltrim($file, "[");
$file = rtrim($file, "]");


$newfile = fopen("tweets-hashtags-trending/tweets-hashtags-trending.json", "w") or die("Unable to open file!");
fwrite($newfile, $file);
fclose($newfile);

$path = "tweets-hashtags-trending/tweets-hashtags-trending.json";
$json_file = file_get_contents($path);
$json = json_decode($json_file);

foreach($json->trends as $trend) {

	$name = $trend->name;
	$url = $trend->url;
	$query = $trend->query;
	$promoted_content = $trend->promoted_content;
	$tweet_volume = $trend->tweet_volume;
	$src_id = str_replace("#","",$name);
	
		$stmt = $mysqli->prepare("INSERT INTO twitter_hashtag_trending (name,url,query,promoted_content,tweet_volume,src_id,update_date) VALUES (?,?,?,?,?,?,?)");
                $stmt -> bind_param('sssiiss',$name,$url,$query,$promoted_content,$tweet_volume,$src_id,$update_date);
                $stmt -> execute();	

                $error = $mysqli->errno . ' ' . $mysqli->error;



}

	?>