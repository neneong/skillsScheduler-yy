<?php
require_once "../lib/DB.php";

$id = $_POST['id'];
$sqlDelete = "UPDATE vacation SET `assign` = 3 WHERE id=$id";

DB::execute($sqlDelete);
?>