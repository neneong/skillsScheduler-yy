<?php


require_once("./lib/DB.php");
require_once("./lib/lib.php");
require_once("./lib/Session.php");

$db = new DB();

$id = $_POST['id'];
$password = $_POST['password'];


$query = "
    SELECT * FROM
    user
    WHERE id = ? AND password = ?
";

$bind = array($id, $password);

$user = $db->fetch($query, $bind);


if (!$user) {
    back("아이디 혹은 비밀번호를 다시 확인해주세요");
}
session()->set('user', $user);
session()->set('grade', $user->grade);
setcookie('grade', $user->grade);
setcookie('name', $user->name);

if ($user->grade == 4) {
    redirect("./Teacher.php", "로그인 하셨습니다.");

} else {
    redirect("./Student.php", "로그인 하셨습니다.");

}