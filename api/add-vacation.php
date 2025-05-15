<?php
require_once "../lib/DB.php";
require_once "../lib/lib.php";

$id = getFinalId('id','vacation');

$count = getFinalId('count','vacation');



if($count == 1 && $id == 1){
}else{
    $count += 1;
}

$student = isset($_POST['student']) ? $_POST['student'] : "";
$title = isset($_POST['title']) ? $_POST['title'] : "";

$start = isset($_POST['start']) ? $_POST['start'] : "";
$end = isset($_POST['end']) ? $_POST['end'] : "";

$sqlInsert = "INSERT INTO vacation (count, student, start ,end ,title) VALUES (?,?,?,?,?)";

$bind = array($count,$student,$start,$end,$title);

DB::execute($sqlInsert, $bind);


?>