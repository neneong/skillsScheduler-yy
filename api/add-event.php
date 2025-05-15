<?php
require_once "../lib/DB.php";

$title = isset($_POST['title']) ? $_POST['title'] : "";
$content = isset($_POST['content']) ? $_POST['content'] : "";

$start = isset($_POST['start']) ? $_POST['start'] : "";
$end = isset($_POST['end']) ? $_POST['end'] : "";

$sqlInsert = "INSERT INTO schedule (title,content,start,end) VALUES ('".$title."','".$content."','".$start."','".$end ."')";


DB::execute($sqlInsert);

?>