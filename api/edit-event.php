<?php
require_once "../lib/DB.php";

$id = $_POST['id'];
$title = $_POST['title'];
$start = $_POST['start'];
$end = $_POST['end'];

$sqlUpdate = "UPDATE schedule SET title=?, start = ?, end = ? WHERE id = ?";

$bind = array($title,$start,$end,$id);

DB::execute($sqlUpdate,$bind);

?>