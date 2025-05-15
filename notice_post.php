<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="./css/fontawesome-free-5.15.4-web/css/all.min.css">
    <link rel="stylesheet" href="./css/bootstrap-3.3.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/notice_post.css">
</head>


<style>
    hr{
        width: 60%;
        background: #292960;
        height: 5px;
    }

    form>button{
        background-color: #46469e;
        border: none;
    }

    p{
        width: 60%;
    }

    @media screen and (max-width:480px) {
        #post>.post-wrap {
            width: 70%;
            margin-left: 7%;

        }
    }
</style>

<body>
    <div class="wrap">

        <?php
        require_once("header.php");
        require_once("./lib/DB.php");
        require_once("./lib/lib.php");
        require_once("./lib/Session.php");

        $id = $_GET['id'];

        $sql = "SELECT * FROM notice WHERE id = $id";

        $post = DB::fetch($sql);
        ?>
        <section id="post">
            <div class="post-wrap">
                <div class="post-inf">
                    <div class="post-tit">
                        <?php echo ("<h3>$post->title</h3>"); ?>
                    </div>
                    <div class="topinfo">
                        <?php echo ("<p>$post->date</p>"); ?>
                    </div>
                </div>
                <hr>
                <div class="post-contents">
                    <?php echo "<p>$post->contents</p>" ?>
                </div>
                <form action="./notice.php">
                    <button type="button" class="btn btn-warning"><a href="./notice.php">목록으로 돌아가기</a></button>
                </form>
            </div>
        </section>
    </div>
    <?php
    require_once("footer.php");
    ?>

</body>

</html>