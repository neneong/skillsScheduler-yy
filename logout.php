<?php
require_once("./lib/Session.php");
require_once("./lib/lib.php");
require_once("./lib/DB.php");

session()->remove('user');
session()->remove('grade');

setcookie('grade', -1);
setcookie('name', -1);

redirect("./", "로그아웃 됐습니다.");