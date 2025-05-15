<?php
require_once "../lib/DB.php";

$id = $_POST['id'];
$sqlDelete = "DELETE from schedule WHERE id=$id";

DB::execute($sqlDelete);
?>