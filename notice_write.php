<?php

require_once("header.php");
require_once("./lib/DB.php");
require_once("./lib/lib.php");
require_once("./lib/Session.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>글 쓰기</title>
    <link rel="stylesheet" href="./css/fontawesome-free-5.15.4-web/css/all.min.css">
    <link rel="stylesheet" href="./css/bootstrap-3.3.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/add_comment.css">
    <script src="./js/main.js"></script>
    <script src='assets/js/jquery-1.10.2.js' type="text/javascript"></script>
    <script src='assets/js/jquery-ui.custom.min.js' type="text/javascript"></script>
    <script src='assets/js/fullcalendar.js' type="text/javascript"></script>
</head>
<style>
    .write-Wrap {
        margin-top: 30px;
    }

    .btn-box>input[type=submit] {
        border-radius: 6px;
        padding: 1%;
        border: 1px solid black;
        background-color: black;
        color: white;
        width: 9%;
        font-size: 1.5rem;


    }

    button,
    html input[type=button],
    input[type=reset],
    input[type=submit] {
        border: 1px solid black;
        background-color: white;
        color: black;
        padding: 1%;
        border-radius: 6px;
        width: 9%;
        text-align: center;
        font-size: 1.5rem;



    }

    .notice>h1 {
        font-weight: bolder;
        text-align: center;

    }

    @media screen and (max-width:480px) {

        .btn-box>input[type=submit] {
            border-radius: 6px;
            padding: 2%;
            border: 1px solid black;
            background-color: black;
            color: white;
            width: 20%;
            font-size: 1.5rem;


        }

        button,
        html input[type=button],
        input[type=reset],
        input[type=submit] {
            border: 1px solid black;
            background-color: white;
            color: black;
            padding: 2%;
            border-radius: 6px;
            width: 20%;
            text-align: center;
            font-size: 1.5rem;



        }

        .write-Wrap {
            margin-top: 0;
        }

        .notice>h1 {
            font-weight: bolder;

        }

    }
</style>

<body>

    <div class="write-Wrap">
        <div class="write">
            <form action="write_ok.php" method="post">
                <div class="notice">
                    <h1>공지사항</h1>
                    <hr>
                </div>

                <div class="write-tit">
                    <label for="title" class="title">제목</label>
                    <input type="text" name="title" id="title" placeholder="제목 입력">
                </div>

                <div class="content">
                    <label for="content" class="title">내용</label>
                    <textarea name="content" id="content" cols="40" rows="20" placeholder="내용 입력"></textarea>
                </div>

                <div class="btn-box">
                    <input type="submit" value="완료" class="btn btn-dark">
                    <a href="./notice.php"><input type="button" href="./notice.php" value="취소" class="btn btn-dark"></a>
                </div>
            </form>
        </div>
    </div>

    </div>


    <?php
    require_once("footer.php");
    ?>
</body>

</html>