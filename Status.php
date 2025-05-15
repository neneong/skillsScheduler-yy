<?php
ini_set('display_errors', '0');

require_once("./lib/DB.php");
require_once("./lib/lib.php");
require_once("./lib/Session.php");



$mobile_agent = "/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/";



if (preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT'])) {
    $agent = "MOBILE";
} else {
    $agent = "PC";
}



if (user()->grade < 4) exit("잘못된 접근입니다.");

$db = new DB();
$user_name = $_SESSION["user"]->name;

if ($agent == "MOBILE") {
    $LIST_SIZE = 5;
} else {
    $LIST_SIZE = 20;
}

$MORE_PAGE = 3;

$page = $_GET['page'] ? intval($_GET['page']) : 1;

$page_count = DB::fetch("SELECT CEIL( COUNT(*)/$LIST_SIZE ) as page FROM vacation")->page;

$start_page = max($page - $MORE_PAGE, 1);
$end_page = min($page + $MORE_PAGE, $page_count);
$prev_page = max($start_page - $MORE_PAGE - 1, 1);
$next_page = min($end_page + $MORE_PAGE + 1, $page_count);

$offset = ($page - 1) * $LIST_SIZE;

if (empty($_GET['date'])) {
    $sql = "SELECT * FROM vacation v ORDER BY v.id DESC LIMIT $offset, $LIST_SIZE";
    $date = "전체";
} else {
    $date = $_GET['date'];

    $sql = "SELECT * FROM vacation v WHERE v.start LIKE '%$date%' ORDER BY v.id DESC LIMIT $offset, $LIST_SIZE";
}

$list = $db->fetchAll($sql);

if (empty($_GET['date'])) {
    $query = "SELECT DISTINCT date_format(start, '%Y년') as data FROM vacation v";
    $lists = $db->fetchAll($query);
} else if (strlen($_GET['date']) == 4) {
    $getDate = $_GET['date'];
    $query = "SELECT DISTINCT date_format(start, '%Y년 %m월') as data FROM vacation v WHERE v.start LIKE '%$getDate%'";
    $lists = $db->fetchAll($query);
} else {
    $getDate = $_GET['date'];
    $getDate = substr($getDate, 0, 4);
    $query = "SELECT DISTINCT date_format(start, '%Y년 %m월') as data FROM vacation v WHERE v.start LIKE '%$getDate%'";
    $lists = $db->fetchAll($query);
}


?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>히스토리 내역</title>

    <link rel="stylesheet" href="./css/fontawesome-free-5.15.4-web/css/all.min.css">
    <link rel="stylesheet" href="./css/bootstrap-3.3.2-dist/js/bootstrap.min.js">
    <link rel="stylesheet" href="./css/bootstrap-3.3.2-dist/css/bootstrap.min.css">
    <script src="./js/main.js"></script>
    <script src="./js/jquery-3.6.0.min.js"></script>
    <script src='assets/js/jquery-1.10.2.js' type="text/javascript"></script>
    <link rel="stylesheet" href="./css/data.css">
</head>
<style>
    a {
        text-decoration: unset;
        font-style:unset;
    }

    .history-wrap {
        width: 100%;
        height: 450px;
        overflow: auto
    }

    #main-history .history-wrap table {
        position: absolute;
        width: 80%;
    }

    #main-history .history-wrap tbody td {
        padding: 13px;
        width: 150px;
    }

    .wrap{
        overflow:hidden;
		height:auto;
    }

    .zmdi{
        text-decoration: unset;
        font-style:unset;
    }

    .pagenum {
        display: inline-block;
        width: 25px;
        border: 1px solid transparent;
        color: gray;
        font-weight: bold;
        text-decoration: none;
        text-align: center;
    }

    .pagenum:hover {
        color: orange;
        border: 1px solid orange;
    }

    .pagenum.current {
        color: orange;
        text-decoration: underline;
    }

    .move_btn {
        color: gray;
    }

    .disabled {
        color: silver;
    }

    .paging_area {
        text-align: center;
        position: relative;
        bottom: 0;
        display: flex;
        justify-content: center;
        width: 100%;
        transform: translateY(<?= count($list) * 50; ?>px);
        
    }

    footer {
        position:relative;
        transform: translateY(<?= count($list) * 60; ?>px);
    }

    @media screen and (max-width:480px) {

        #sidebar {
            position: relative;
            width: 100%;
        }

        #viewport {
            padding-left: 0;
        }

        .history-wrap {
            width: 100%;
            height: 450px;
            overflow: auto
        }

        #main-history .history-wrap table {
            width: 95%;
        }

        #main-history .history-wrap tbody td {
            width: 150px;
            padding: 11px;

        }

        #sidebar .nav a {
            background: none;
            border-bottom: 1px solid #e0e0e0;
            color: #767676;
            font-size: 14px;
            padding: 16px 24px;
            text-align: center;
        }

        .paging_area {
            position:relative;
            left: 0%;
            transform: translateY(<?= count($list) * 35; ?>px);
        }

        footer {
            position:relative;
            transform: translateY(<?= count($list) * 40; ?>px);
        }
    }
</style>
<script>
    $(document).ready(function() {

        $(() => {
            setInterval(function() {
                if (document.documentElement.scrollTop >= 126) {
                    console.log("alert")
                    $("#sidebar").css({
                        "margin-top": "-70px"
                    })
                } else {
                    $("#sidebar").css({
                        "margin-top": "0px"
                    })
                }
            }, 100);


        })
    })
</script>

<body>


    <?php require_once("header.php");?>

    <div class="wrap">
        <div id="left-sidebar">
            <div class="sidebar-wrap">
                <div class="side-main">
                    <div id="viewport">
                        <!-- Sidebar -->
                        <div id="sidebar">
                            <header>
                                <a href="#">히스토리 내역</a>
                                <span class="small" style="font-size:11px;color:#9c9c9c;">* 시작일 기준</span>
                            </header>
                            <ul class="nav">
                                <li><a href="./Status.php"><i class="zmdi">전체보기</a></i></li>
                                <?php foreach ($lists as $item) : ?>
                                    <?php
                                    $choose = $item->data;

                                    if (strlen($choose) == 7) {
                                        $year = explode("년", $item->data);
                                        $date = $year[0];
                                    } else {
                                        $year = explode("년 ", $item->data);
                                        $month = explode("월", $year[1]);
                                        $date = $year[0] . "-" . $month[0];
                                    }

                                    ?>
                                    <li>
                                        <a href="?date=<?= $date ?>">
                                            <i class="zmdi"><?= $item->data ?></i>

                                        </a>
                                    </li>

                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="main">
        <div class="main-wrap">
            <div id="main-senter">
                <div class="senter-wrap">
                    <div class="title">
                        <div class="title-wrap">
                            <small>
                                <?php 
                                    $today = date("Y년 m월");
                                ?>
                                <br>학생들이 휴가를 쓴 총 횟수
                            </small>
                            <h1 class="tit"><span>
                                    <?php

                                    if (empty($_GET['date'])) {
                                        $sql = "SELECT COUNT(*) AS count FROM vacation v ORDER BY v.id";
                                    } else {
                                        $date = $_GET['date'];
                                        $sql = "SELECT COUNT(*) AS count FROM vacation v WHERE v.start LIKE '%$date%' ORDER BY v.id";
                                    }
                                    $counts = $db->fetchAll($sql);
                                    foreach ($counts as $count) {
                                        echo $count->count;
                                    }

                                    ?>
                                </span>회</h1>
                        </div>
                    </div>
                </div>
            </div>

            <div id="main-history">
                <div class="history-wrap">
                    <div class="row">
                        <table class="table" width="100%" cellspacing="0" cellpadding="0">
                            <thead>
                                <th scope="col">ID</th>
                                <th scope="col">학생</th>
                                <th scope="col">사유</th>
                                <th scope="col">날짜</th>
                                <th scope="col">내역</th>
                                <th scope="col">현황</th>
                            </thead>
                            <tbody>
                                <?php foreach ($list as $item) : ?>
                                    <tr>
                                        <td><?= $item->count ?></td>
                                        <td><?= $item->student ?></td>
                                        <td> <span class="ico"><?= $item->title ?></span></td>
                                        <td><?= $item->start ?> ~ <?= $item->end ?></td>
                                        <td>
                                            <?php if ($item->assign == 0) : ?>
                                                0
                                            <?php elseif ($item->assign == 1) : ?>
                                                <?php
                                                $start = $item->start;
                                                $end = $item->end;
                                                $start = new DateTime($start);
                                                $end = new DateTime($end);
                                                $a = date_diff($end, $start);
                                                echo $a->d + 1 . "일";
                                                ?>
                                            <?php else : ?>
                                                0
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($item->assign == 0) : ?>
                                                <p class="un">미승인</p>
                                            <?php elseif ($item->assign == 1) : ?>
                                                <p class="ap">승인</p>
                                            <?php elseif ($item->assign == 2) : ?>
                                                <p class="re">반려</p>
                                            <?php elseif ($item->assign == 3) : ?>
                                                <p class="de">삭제</p>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class='paging_area'>
                <?php if ($start_page > 1) : ?>
                    <a class='move_btn' href="<?= "$PHP_SELP?page=$prev_page" ?>">« 이전</a>
                    <a class='pagenum' href="<?= "$PHP_SELP?page=1" ?>">1</a> ...
                <?php else : ?>
                    <span class='move_btn disabled'>« 이전</span>
                <?php endif ?>

                <?php for ($p = $start_page; $p <= $end_page; $p++) : ?>
                    <a class='pagenum <?= ($p == $page) ? "current" : "" ?>' href="<?= "$PHP_SELP?page=$p" ?>">
                        <?= $p ?>
                    </a>
                <?php endfor ?>

                <?php if ($end_page < $page_count) : ?>
                    ... <a class='pagenum' href="<?= "$PHP_SELP?page=$page_count" ?>"><?= $page_count ?></a>
                    <a class='move_btn' href="<?= "$PHP_SELP?page=$next_page" ?>">다음 »</a>
                <?php else : ?>
                    <span class='move_btn disabled'>다음 »</span>
                <?php endif ?>
            </div>
        </div>
    </div>
    </div>
    <?php require_once("footer.php"); ?>
</body>

</html>