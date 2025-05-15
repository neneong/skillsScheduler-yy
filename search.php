<?php

require_once("./lib/DB.php");
require_once("./lib/lib.php");
require_once("./lib/Session.php");

$db = new DB();

$index = $_POST['index'];

$query = "
    SELECT * FROM
    schedule
    WHERE date = ?
";

$bind = array($id);

$res = $db->fetch($query);


