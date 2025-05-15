<?php
ini_set('display_errors', '0');

require_once("./lib/DB.php");
require_once("./lib/lib.php");
require_once("./lib/Session.php");

$a = getCookie("grade");
?>
<style>
    .header-name {
        color: white;
        position: relative;
        left: -1%;
    }
    .header-list {
        display: flex;
        justify-content: space-around;
    }
    .list-menu {
        position: relative;
      
    }
    @media screen and (max-width:480px){
        .header-name {
            position: relative;
            left: -1%;
        }
    }
</style>
<header>
    <div id="header-list">

        <!-- 4 = 선생님 로그인 / 0 = 비로그인 상태 / else = 학생 로그인 -->
        <?php if ($a == 4) : ?>
            <a href="./Teacher.php"><i class="far fa-calendar"></i></a>
        <?php elseif ($a > 0) : ?>
            <a href="./Student.php"><i class="far fa-calendar"></i></a>
        <?php else : ?>
            <a href="./index.php"><i class="far fa-calendar"></i></a>
        <?php endif; ?>
        <ul class="list-menu">
            <li class="menu"><a href="./notice.php">공지사항</a></li>
            <?php if (!empty(user())) : ?>
                <?php if (user()->grade < 4) : ?>
                    <li class="menu"><a href="./Data.php">마이페이지</a></li>
                <?php elseif (user()->grade == 4) : ?>
                    <li class="menu"><a href="./Status.php">학생휴가내역</a></li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
        <?php if (session()->has('user')) : ?>
            <!--헤더 데이터 출력-->
            <li class="header-name" style=""><?php echo $_SESSION["user"]->name ?></li>
            <li class="main logout"><a href="./logout.php">로그아웃</a></li>
        <?php else : ?>
            <li class="main login"><a href="./login.php">로그인</a></li>
        <?php endif; ?>
        <!-- 세션 데이터 확인 -->



    </div>
</header>