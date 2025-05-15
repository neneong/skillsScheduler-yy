<?php
require_once("./lib/DB.php");
require_once("./lib/lib.php");
require_once("./lib/Session.php");

$db = new DB();

$id = $_POST['id'];
$name = $_POST['name'];
$password = $_POST['password'];
$grade = $_POST['grade'];

$query = "
    SELECT * FROM
    users
    WHERE id = ?
";

$bind = array($id);

$user = $db->fetch($query);

if (!$user) {
    $sql = "INSERT INTO user(`id`, `password`, `name`, `grade`) VALUES ('$id', '$password', '$name', $grade)";
}

$db->execute($sql);

redirect("./", '회원가입 완료');
