<?php
require_once("header.php");
require_once("./lib/DB.php");
require_once("./lib/lib.php");
require_once("./lib/Session.php");



?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/fontawesome-free-5.15.4-web/css/all.min.css">
    <link rel="stylesheet" href="./css/bootstrap-3.3.2-dist/js/bootstrap.min.js">
    <link rel="stylesheet" href="./css/bootstrap-3.3.2-dist/css/bootstrap.min.css">
    <script src="./js/main.js"></script>
    <script src="./js/jquery-3.6.0.min.js"></script>
    <link href='assets/css/fullcalendar.print.css' rel='stylesheet' media='print' />
    <link href='assets/css/fullcalendar.css' rel='stylesheet' />
    <script src='assets/js/jquery-1.10.2.js' type="text/javascript"></script>
    <script src='assets/js/jquery-ui.custom.min.js' type="text/javascript"></script>
    <script src='assets/js/fullcalendar.js' type="text/javascript"></script>
    <link rel="stylesheet" href="./css/teacher.css">
</head>

<?php
$request_uri = $_SERVER['REQUEST_URI'];

ini_set('display_errors', '0');

if (user()->grade < 4) exit("잘못된 접근입니다.");

$db = new DB();

$mobile_agent = "/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/";

if (preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT'])) {
    $agent = "MOBILE";
    $LIST_SIZE = 3;
    $MORE_PAGE = 3;

    $page = $_GET['page'] ? intval($_GET['page']) : 1;
    $page_count = DB::fetch("SELECT CEIL( COUNT(*)/$LIST_SIZE ) as page FROM schedule")->page;
    $start_page = max($page - $MORE_PAGE, 1);
    $end_page = min($page + $MORE_PAGE, $page_count);
    $prev_page = max($start_page - $MORE_PAGE - 1, 1);
    $next_page = min($end_page + $MORE_PAGE + 1, $page_count);
    $offset = ($page - 1) * $LIST_SIZE;

    $page2 = $_GET['page2'] ? intval($_GET['page2']) : 1;
    $page_count2 = DB::fetch("SELECT CEIL( COUNT(*)/$LIST_SIZE ) as page FROM vacation")->page;
    $start_page2 = max($page2 - $MORE_PAGE, 1);
    $end_page2 = min($page2 + $MORE_PAGE, $page_count2);
    $prev_page2 = max($start_page2 - $MORE_PAGE - 1, 1);
    $next_page2 = min($end_page2 + $MORE_PAGE + 1, $page_count2);
    $offset2 = ($page2 - 1) * $LIST_SIZE;
} else {
    $agent = "PC";
}


if ($agent == "MOBILE") {
    $sql = "SELECT * FROM `vacation` v WHERE assign = 1 ORDER BY v.id DESC LIMIT $offset2, $LIST_SIZE";
    $lists = DB::fetchAll($sql);

    $sql2 = "SELECT * FROM schedule s ORDER BY s.id DESC LIMIT $offset, $LIST_SIZE;";
    $ans = DB::fetchAll($sql2);
} else {
    $sql = "SELECT * FROM `vacation` WHERE assign = 1";
    $lists = DB::fetchAll($sql);
    $sql2 = "SELECT * FROM schedule;";
    $ans = DB::fetchAll($sql2);
}

$sql3 = "SELECT * FROM `vacation` WHERE assign = 0";
$res = DB::fetchAll($sql3);

?>

<style>
    header {
        margin-bottom: 10px;
    }

    body {
        text-align: center;
        font-size: 14px;
        font-family: "Helvetica Nueue", Arial, Verdana, sans-serif;
        background-color: #eee;
    }

    #wrap {
        width: 1100px;
        margin: 0 auto;
    }

    #external-events {
        float: left;
        width: 150px;
        padding: 0 10px;
        text-align: left;
    }

    #external-events h4 {
        font-size: 16px;
        margin-top: 0;
        padding-top: 1em;
    }

    .external-event {
        /* try to mimick the look of a real event */
        margin: 10px 0;
        padding: 2px 4px;
        background: #3366CC;
        color: #fff;
        font-size: .85em;
        cursor: pointer;
    }

    #external-events p {
        margin: 1.5em 0;
        font-size: 11px;
        color: #666;
    }

    #external-events p input {
        margin: 0;
        vertical-align: middle;
    }

    .current{
		border:1px solid;
        padding-left:3px;
	}

    #calendar {
        float: left;
        margin: -15px auto;
        width: 900px;
        background-color: #FFFFFF;
        border-radius: 6px;
        box-shadow: 0 1px 2px #C3C3C3;
    }

    #mobile {
        display: none;
    }

    .t-vacation-wrap{
        width:100%; 
        height:450px; 
        overflow:auto;
    }

    .close {
        font-size: 5rem;
        position: absolute;
        right: 5%;
        top: 3%;
    }

    .modal .content>.title {
        font-size: 24px;
        position: fixed;
        top: 20px;
        left: 35%;
        font-weight: bold;

    }

    #check {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        font-size: 19px;

    }

    .modal .content>button:last-child {
        position: absolute;
        bottom: 8%;
        width: 30%;
        height: 7%;
        right: 18%;
        border-radius: 6px;
        padding: 1.5px 0px;
        border: none;
        background-color: #1076E7;
        left: 200px;
        font-weight: bold;

    }

    .modal .content>.decline {
        position: absolute;
        bottom: 8%;
        width: 30%;
        height: 7%;
        left: 18%;
        border-radius: 6px;
        padding: 1.5px 0px;
        border: none;
        background-color: white;
        color: black;
        border: 1px black solid;
        font-weight: bold;
    }

    #add-calander{
        margin-top:0;
        width:100%;
        height:100%;
        background-color: rgba(0, 0, 0, 0.493);
        z-index:9999;
    }

    #add-calander>.content{
        margin-top:0%;
        width:20%;
        height:600px;
        margin-left:0%;
        background-color:white;
        border-radius: 20px;
        padding-top:20px;
    }

    footer {
        z-index: 99999;
    }

    @media screen and (max-width:1500px) {
        #calendar {
            transform: translateX(-15%);
            transition: all .3s linear;
        }

    }

    @media screen and (max-width:480px) {
        .pagenum {
            font-size: 17;
            margin:0 4px;
        }

        #calendar {
            display: none;

        }

        #mobile {
            display: block;
        }

        #right-sidebar {
            width: 100%;
            position: relative;
        }

        .T-title {
            font-size: 2rem;
            font-weight: bold;
            background-color: #EEEEEE;

        }



    }
</style>
<script>
    // 모달창 팝업

    $(() => {
        $('.fa-check-circle').click(function() {
            var c = $(this).data("acc")

            $(`.modal[data-set='${c}']`).fadeToggle();
        });
    })

    $(() => {
        $('.fa-times-circle').click(function() {
            var c = $(this).data("dec")
            console.log(c)
            $(`.modal[data-set='${c}']`).fadeToggle();
        });
    })

    $(() => {
        $('.close').click(function() {
            var c = $(this).data("clo")
            console.log(c)
            $(`.modal[data-set='${c}']`).fadeToggle();
        });
    })

    $(() => {
        $('.accept').click(function() {
            var c = $(this).data("accept")
            $.ajax({
                    url: '/api/vacation-judge.php',
                    data: 'id=' + c + '&assign=' + "1",
                    type: "POST",
                })

                .done(function(json) {
                    console.log("accept success!")
                    location.reload();
                })
            $(`.modal[data-set='${c}']`).fadeToggle();
        });
    })

    $(() => {
        $('.decline').click(function() {
            var c = $(this).data("decline")
            console.log(c);
            $.ajax({
                url: '/api/vacation-judge.php',
                data: 'id=' + c + '&assign=' + "2",
                type: "POST",
                success: function(data) {
                    console.log("decline success!")
                    location.reload();
                }
            });
            $(`.modal[data-set='${c}']`).fadeToggle();
            console.log(c)
            
        });
    })

    $(() => {
        $('.accept-cal').click(function() {
            title = document.getElementById("tit-text").value;

            str = document.getElementById("start-date");
            strs = new Date(str.value)
            strs = strs.toISOString()

            end = document.getElementById("end-date");
            ends = new Date(end.value)
            ends = ends.toISOString()

            content = document.getElementById("reason-text").value;
            console.log(content)

            if (ends >= strs) {
                $.ajax({
                    url: '/api/add-event.php',
                    data: 'title=' + title + '&content=' + content + '&start=' + strs + '&end=' + ends,
                    type: "POST",
                    success: function(data) {
                        console.log("success!");
                        $('#calendar').fullCalendar('refetchEvents');
                    }
                });

                $("#add-calander").fadeToggle();

                tit = document.getElementById("tit-text")
                str = document.getElementById("start-date");
                end = document.getElementById("end-date");
                content = document.getElementById("reason-text");
                tit.value = "";
                str.value = "";
                end.value = "";
                content.value = "";
            } else {
                alert("종료 날짜는 시작 날짜보다 뒤여야만 합니다.")
            }
        });
    })

    $(() => {
        $('.cancel-cal').click(function() {
            $(`#add-calander`).fadeToggle();
        });
    })


    $(() => {
        $('.ok').click(function() {
            var id = document.getElementById("del").dataset.id;
            var color = document.getElementById("del").dataset.color;
            if (color == 0) {
                $.ajax({
                    type: "POST",
                    url: "/api/delete-vacation.php",
                    data: "&id=" + id,
                    success: function(response) {
                        $('#calendar').fullCalendar('removeEvents', id);
                        console.log("delete suceess!")
                    }
                });
            } else if (color == 1) {
                $.ajax({
                    type: "POST",
                    url: "/api/delete-event.php",
                    data: "&id=" + id,
                    success: function(response) {
                        $('#calendar').fullCalendar('removeEvents', id);
                        console.log("delete suceess!")
                    }
                });
            }
            $(`.modal-details`).fadeToggle();

        });
    })


    $(document).ready(function() {

        function getCookie(name) {

            var nameOfCookie = name + "=";

            var x = 0;

            while (x <= document.cookie.length) {
                var y = (x + nameOfCookie.length);
                if (document.cookie.substring(x, y) == nameOfCookie) {

                    if ((endOfCookie = document.cookie.indexOf(";", y)) == -1)

                        endOfCookie = document.cookie.length;

                    return unescape(document.cookie.substring(y, endOfCookie));

                }

                x = document.cookie.indexOf(" ", x) + 1;

                if (x == 0)
                    break;

            }

            return "";

        }
        const offset = new Date().getTimezoneOffset() * 60000;
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        /*  className colors

        className: default(transparent), important(red), chill(pink), success(green), info(blue)

        */


        /* initialize the external events
        -----------------------------------------------------------------*/

        $('#external-events div.external-event').each(function() {

            // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
            // it doesn't need to have a start or end
            var eventObject = {
                title: $.trim($(this).text()) // use the element's text as the event title
            };

            // store the Event Object in the DOM element so we can get to it later
            $(this).data('eventObject', eventObject);

            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 999,
                revert: true, // will cause the event to go back to its
                revertDuration: 0 //  original position after the drag
            });

        });


        /* initialize the calendar
        -----------------------------------------------------------------*/

        var calendar = $('#calendar').fullCalendar({

            header: {
                left: '',
                center: 'title',
                right: 'prev,next today'
            },
            editable: true,
            firstDay: 1, //  1(Monday) this can be changed to 0(Sunday) for the USA system
            selectable: true,
            defaultView: 'month',

            axisFormat: 'h:mm',
            columnFormat: {
                month: 'ddd', // Mon
                week: 'ddd d', // Mon 7
                day: 'dddd M/d', // Monday 9/7
                agendaDay: 'dddd d'
            },
            titleFormat: {
                month: 'yyyy년 MMMM', // September 2009
                week: "MMMM yyyy", // September 2009
                day: 'MMMM yyyy' // Tuesday, Sep 8, 2009
            },
            allDaySlot: false,
            selectHelper: true,
            select: function(start, end, allDay) {

                $(`#add-calander`).fadeToggle();

                starts = new Date(start - offset).toISOString().slice(0, 10);
                str = document.getElementById("start-date");
                str.value = starts;

                ends = new Date(end - offset).toISOString().slice(0, 10);
                end = document.getElementById("end-date");
                end.value = ends;

                calendar.fullCalendar('unselect');
            },
            eventDrop: function(event, delta) {

                console.log(event.source.color);

                if (event.source.color == "rgb(255,108,112)") {
                    console.log(event)
                    console.log()
                    var start = new Date(event.start - offset).toISOString();
                    var end = new Date(event.end - offset).toISOString();
                    $.ajax({
                        url: '/api/edit-vacation.php',
                        data: 'title=' + event.title.split("] ")[event.title.split("] ").length - 1] + '&start=' + start + '&end=' + end + '&id=' + event.id,
                        type: "POST",
                        success: function(response) {
                            console.log("AAAAAA")
                        }
                    });
                } else if (event.source.color == "orange") {
                    console.log(event)
                    var start = new Date(event.start - offset).toISOString();

                    var end = new Date(event.end - offset).toISOString();
                    $.ajax({
                        url: '/api/edit-event.php',
                        data: 'title=' + event.title + '&start=' + start + '&end=' + end + '&id=' + event.id,
                        type: "POST",
                        success: function(response) {
                            console.log("BBBBBBB")
                        }
                    });
                }
            },

            eventClick: function(event) {
                if (event.source.color == "rgb(255,108,112)") {
                    $(`.modal-details`).fadeToggle();

                    $.ajax({
                            url: '/api/search-vacation.php', // use the `url` property
                            data: 'id=' + event.id,
                            type: "POST",
                            dataType: "json"
                        })

                        .done(function(json) {
                            console.log(json)
                            document.getElementById("detail-h").innerHTML = "기능반 휴가"
                            document.getElementById("name-title").innerHTML = "이름";
                            document.getElementById("name-id").innerHTML = json[0].student;
                            document.getElementById("date-id").innerHTML = json[0].start + " ~ " + json[0].end;
                            document.getElementById("reason-id").innerHTML = json[0].title;
                            console.log(json[0])
                            document.getElementById("del").dataset.id = json[0].id;
                            document.getElementById("del").dataset.color = "0";

                        })


                } else if (event.source.color == "orange") {
                    $(`.modal-details`).fadeToggle();

                    $.ajax({
                            url: '/api/search-event.php', // use the `url` property
                            data: 'id=' + event.id,
                            type: "POST",
                            dataType: "json"
                        })

                        .done(function(json) {
                            console.log(json)
                            document.getElementById("detail-h").innerHTML = "기능반 일정"
                            document.getElementById("name-title").innerHTML = "제목";
                            document.getElementById("name-id").innerHTML = json[0].title;
                            document.getElementById("date-id").innerHTML = json[0].start + " ~ " + json[0].end;
                            document.getElementById("reason-id").innerHTML = json[0].content;
                            console.log(json[0].value)
                            document.getElementById("del").dataset.id = json[0].id;
                            document.getElementById("del").dataset.color = "1";
                        })
                }




            },

            eventResize: function(event, dayDelta, minuteDelta, revertFunc) {

                if (event.source.color == "rgb(255,108,112)") {
                    console.log(event.start)
                    var start = new Date(event.start - offset).toISOString();
                    console.log(start)
                    var end = new Date(event.end - offset).toISOString();
                    $.ajax({
                        url: '/api/edit-vacation.php',
                        data: 'title=' + event.title + '&start=' + start + '&end=' + end + '&id=' + event.id,
                        type: "POST",
                        success: function(response) {

                        }
                    });
                } else if (event.source.color == "orange") {
                    console.log(event.start)
                    var start = new Date(event.start - offset).toISOString();
                    console.log(start)
                    var end = new Date(event.end - offset).toISOString();
                    $.ajax({
                        url: '/api/edit-event.php',
                        data: 'title=' + event.title + '&start=' + start + '&end=' + end + '&id=' + event.id,
                        type: "POST",
                        success: function(response) {

                        }
                    });
                }

            },
            droppable: true, // this allows things to be dropped onto the calendar !!!
            drop: function(date, allDay) { // this function is called when something is dropped

                // retrieve the dropped element's stored Event Object
                var originalEventObject = $(this).data('eventObject');

                // we need to copy it, so that multiple events don't have a reference to the same object
                var copiedEventObject = $.extend({}, originalEventObject);

                // assign it the date that was reported
                copiedEventObject.start = date;
                copiedEventObject.allDay = allDay;

                // render the event on the calendar
                // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

                // is the "remove after drop" checkbox checked?
                if ($('#drop-remove').is(':checked')) {
                    // if so, remove the element from the "Draggable Events" list
                    $(this).remove();
                }

            },

            eventSources: [

                // your event source
                {
                    url: '/api/fetch-event.php', // use the `url` property
                    color: 'orange', // an option!
                    textColor: 'black' // an option!
                },

                {
                    url: '/api/fetch-vacation.php', // use the `url` property
                    color: 'rgb(255,108,112)', // an option!
                    textColor: 'black' // an option!
                }

                // any other sources...

            ],


        })

    });
</script>

<body>
    <div class="wrap">
        <div id="mobile">
            <ul class="list-group">
                <li class="list-group-item T-title">-기능반 일정-</li>
                <?php foreach ($ans as $an) : ?>
                    <li class="list-group-item "><?= $an->title ?></li>
                <?php endforeach; ?>
                <li class="list-group-item">
                    <div class='paging_area'>
                        <?php if ($start_page > 1) : ?>
                            <a class='move_btn' href="<?= "$PHP_SELP?page=$prev_page" ?>">« 이전</a>
                            <a class='pagenum' href="<?= "$PHP_SELP?page=1" ?>">1</a> ...
                        <?php else : ?>
                            <span class='move_btn disabled'>« 이전</span>
                        <?php endif ?>

                        <?php for ($p = $start_page; $p <= $end_page; $p++) : ?>
                            <a class='pagenum <?= ($p == $page) ? "current" : "" ?>' href="<?= "$PHP_SELP?&page=$p" ?>">
                                <?= $p ?>
                            </a>
                        <?php endfor ?>

                        <?php if ($end_page < $page_count) : ?>
                            ...<a class='pagenum' href="<?= "$PHP_SELP?page=$page_count" ?>"><?= $page_count ?></a>
                            <a class='move_btn' href="<?= "$PHP_SELP?page=$next_page" ?>">다음 »</a>
                        <?php else : ?>
                            <span class='move_btn disabled'>다음 »</span>
                        <?php endif ?>
                    </div>
                </li>

                <li class="list-group-item T-title">-학생 휴가 일정-</li>
                <?php foreach ($lists as $list) : ?>
                    <li class="list-group-item">[<?= $list->student ?>] <?= $list->title ?></li>
                <?php endforeach; ?>
                <li class="list-group-item">
                    <div class='paging_area'>
                        <?php if ($start_page2 > 1) : ?>
                            <a class='move_btn' href="<?= "$PHP_SELP?page2=$prev_page2" ?>">« 이전</a>
                            <a class='pagenum' href="<?= "$PHP_SELP?page2=1" ?>">1</a> ...
                        <?php else : ?>
                            <span class='move_btn disabled'>« 이전</span>
                        <?php endif ?>

                        <?php for ($p = $start_page2; $p <= $end_page2; $p++) : ?>
                            <a class='pagenum <?= ($p == $page2) ? "current" : "" ?>' href="<?= "$PHP_SELP?page2=$p" ?>">
                                <?= $p ?>
                            </a>
                        <?php endfor ?>

                        <?php if ($end_page2 < $page_count2) : ?>
                            ...<a class='pagenum' href="<?= "$PHP_SELP?page2=$page_count2" ?>"><?= $page_count2 ?></a>
                            <a class='move_btn' href="<?= "$PHP_SELP?page2=$next_page2" ?>">다음 »</a>
                        <?php else : ?>
                            <span class='move_btn disabled'>다음 »</span>
                        <?php endif ?>
                    </div>
                </li>
            </ul>
        </div>
        <!-- 메인 (캘린더) 시작 -->
        <section id="main">
            <div id='wrap'>

                <div id='calendar'></div>

                <div style='clear:both'></div>
            </div>
        </section>
        <!-- 메인 (캘린더) 끝 -->

        <!-- 오른쪽 탭 시작 -->
        <section id="right-sidebar">
            <div id="side-date">
                <div class="side-date-wrap">
                    <?php
                    // $today = date("Y년 m월 d일");
                    // echo $today;
                    echo "승인 대기중인 휴가";
                    ?>
                </div>
            </div>

            <div id="t-vacation">
                <div class="t-vacation-wrap">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive project-list">
                                <table class="table table-striped table-centered table-nowrap" width="100%" cellspacing="0" cellpadding="0">
                                    <thead>
                                        <tr>

                                            <th scope="col">이름</th>
                                            <th scope="col">기간</th>
                                            <th scope="col">승인</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($res as $re) : ?>
                                            <tr data-idx=<?= "$re->id" ?>>

                                                <td><?= $re->student ?></td>
                                                <td><?= $re->start ?> ~ <?= $re->end ?></td>

                                                <td>
                                                    <div class="action">
                                                        <i id="check" class="fas fa-check-circle check" data-acc=<?= $re->id ?>></i>

                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </section>
        <!-- 오른쪽 탭 끝 -->

        <!-- 푸터 시작 -->
        <!-- 푸터 끝 -->
    </div>

    <!-- 모달 -->

    <?php foreach ($res as $re) : ?>
        <div class="modal" data-set=<?= $re->id ?>>

            <div class="content">
                <h3 class="title">기능반 휴가</h3>
                <i class="fas close fa-times" data-clo=<?= $re->id ?>></i>
                <div class="name">
                    <h4>이름</h4>
                    <p><?= $re->student ?></p>
                </div>
                <div class="date">
                    <h4>날짜</h4>
                    <div class="date-wrap">
                        <p><?= $re->start ?> ~ <?= $re->end ?></p>
                    </div>
                </div>
                <div class="reason">
                    <h4>사유</h4>
                    <p><?= $re->title ?></p>
                </div>
                <button class="decline" data-decline=<?= $re->id ?>>반려</button>
                <button class="accept" data-accept=<?= $re->id ?>>승인</button>
            </div>
        </div>


    <?php endforeach; ?>


    <div class="modal-details">
        <div class="content">
            <h3 class="title" id="detail-h">기능반</h3>
            <div class="name">
                <h4>이름</h4>
                <p id="name-id"></p>
            </div>
            <div class="date">
                <h4>날짜</h4>
                <p id="date-id"></p>
            </div>
            <div class="reason">
                <h4>사유</h4>
                <p id="reason-id"></p>
            </div>
            <!-- 이 부분 수정하기 -->
            <button class="ok" id="del">삭제하기</button>
            <button class="modal2-x">닫기</button>
        </div>
    </div>

    <div id="add-calander">
        <div class="content">
            <h3 class="title">기능반 일정추가</h3>
            <div class="name">
                <h4 id="name-title">이름</h4>
                <p id="name-id"></p>
                <p><?php echo $_SESSION['user']->name; ?></p>
            </div>
            <div class="tit">
                <h4>제목</h4>
                <input type="text" id="tit-text">
            </div>
            <div class="start">
                <h4>시작 날짜</h4>
                <input type="date" id="start-date">
            </div>
            <div class="end">
                <h4>종료 날짜</h4>
                <input type="date" id="end-date">
            </div>
            <div class="reason">
                <h4>사유</h4>
                <p><input type="text" name="reason" id="reason-text"></p>
            </div>
            <button class="accept-cal" id="ok">확인</button>
            <button class="cancel-cal">닫기</button>
        </div>
    </div>


</body>

</html>

<?php
require_once("footer.php");
?>