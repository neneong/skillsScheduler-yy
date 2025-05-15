<?php

require_once("header.php");
require_once("./lib/DB.php");
require_once("./lib/lib.php");
require_once("./lib/Session.php");



$LIST_SIZE = 5;

$MORE_PAGE = 3;

$page = $_GET['page'] ? intval($_GET['page']) : 1;

$page_count = DB::fetch("SELECT CEIL( COUNT(*)/$LIST_SIZE ) as page FROM notice")->page;

$start_page = max($page - $MORE_PAGE, 1);
$end_page = min($page + $MORE_PAGE, $page_count);
$prev_page = max($start_page - $MORE_PAGE - 1, 1);
$next_page = min($end_page + $MORE_PAGE + 1, $page_count);

$offset = ($page - 1) * $LIST_SIZE;

$sql = "SELECT * FROM notice n ORDER BY n.id DESC LIMIT $offset, $LIST_SIZE";
$lists = DB::fetchAll($sql);

?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="./css/fontawesome-free-5.15.4-web/css/all.min.css">
    <link rel="stylesheet" href="./css/bootstrap-3.3.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/notice.css">
</head>
<style>
    a {
        text-decoration: unset;
        font-style:unset;
    }
    .btn {
        position: fixed;
        left: 90%;
        top: 70%;
    }

    td {
        text-align: center;
    }

    caption {
        font-weight: bold;
        color: black;
        font-size: 30px;
        text-align: center;
        
        }

    .table-wrap{
        width:80%;
        margin:0 auto;
        text-align: center;
    }

    tr :first-child,
    tr :nth-child(2),
    tr :last-child {
        padding: 8px 30px;
        text-align: center;
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
        transform: translateY(<?= $list[0]->count * 65; ?>px);
    }

    @media screen and (max-width: 480px) {
        .btn {
            left: 75%;
            top: 80%;
        }

        th {
            font-size: 15px;
            text-align: center;
            padding: 8px 10px;
            

        }

        

        th :nth-last-child(){
            width:100px;
        }

        td {
            font-size: 13px;
            width: 150px;
        }

        .table-wrap {
            overflow: auto;
            height: 605px;
        }

        .table-wrap{
            width:100%;
            margin:0 auto;
            text-align: center;
        }

        tr :first-child,
        tr :nth-child(2),
        tr :last-child {
            padding: 8px 20px;
            text-align: center;
        }

        tr :nth-child(2) {
            padding: 8px 20px 8px 20px;

        }

        tr :first-child,
        tr :nth-child(3),
        tr :nth-child(4),
        tr :last-child {
            width: 100px;
        }

        .table-wrap>table:nth-child(1)>thead:nth-child(2)>tr:nth-child(1)>th:nth-child(1) {
            width: 100px;
            padding: 8px 0;

        }
    }
</style>

<body>
    <!-- 헤더 시작 -->
    <div class="wrap">
        <?php require_once("header.php"); ?>
        <section id="main">
            <div id="table">
                <div class="table-wrap">
                    <table>
                        <caption>공지사항</caption>
                        <thead>
                            <tr>
                                <th>번호</th>
                                <th>제목</th>
                                <th>작성일</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lists as $list) : ?>
                                <tr>
                                    <td><?= $list->id ?></td>
                                    <td><?php echo ("<a href='./notice_post.php?id=$list->id'>$list->title</a>"); ?></a></td>
                                    <td><?= $list->date ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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


        </section>



        <?php
        if (user()->grade == 4) {
            echo "<input type='button' class='btn btn-primary' value='공지 작성' onClick=\"location.href='./notice_write.php'\">";
        }
        require_once("footer.php");
        ?>

    </div>
</body>

</html>