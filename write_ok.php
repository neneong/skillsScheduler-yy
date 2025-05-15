<?php
require_once("./lib/DB.php");


$title = $_POST['title'];
$content = $_POST['content'];

$content = str_replace("\r\n", "<br>", $content);

$datestring = date (DATE_ISO8601, time());

$sql = "INSERT INTO `notice`(`title`, `contents`,`date`) VALUES ('$title','$content','$datestring')";

DB::execute($sql);

header("Location: /notice.php");

