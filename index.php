<?php

ini_set('display_errors', '0');
require_once("./lib/DB.php");
require_once("./lib/lib.php");
require_once("./lib/Session.php");


if (!empty(user())) {
	if (user()->grade > 3) header("Location: /Teacher.php");
	if (user()->grade < 4) header("Location: /Student.php");
}

$db = new DB();

$LIST_SIZE = 5;

$MORE_PAGE = 3;

$page = $_GET['page'] ? intval($_GET['page']) : 1;
$page_count = DB::fetch("SELECT CEIL( COUNT(*)/$LIST_SIZE ) as page FROM schedule")->page;

$start_page = max($page - $MORE_PAGE, 1);
$end_page = min($page + $MORE_PAGE, $page_count);
$prev_page = max($start_page - $MORE_PAGE - 1, 1);
$next_page = min($end_page + $MORE_PAGE + 1, $page_count);

$offset = ($page - 1) * $LIST_SIZE;

if (empty($_GET['date'])) {
    $sql = "SELECT * FROM schedule s ORDER BY s.id DESC LIMIT $offset, $LIST_SIZE";
    $date = "전체";
} else {
    $date = $_GET['date'];
    $sql = "SELECT * FROM schedule s WHERE s.start LIKE '%$date%' ORDER BY v.id DESC LIMIT $offset, $LIST_SIZE";
}

$lists = $db->fetchAll($sql);

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
	<script src="./js/jquery-3.6.0.min.js"></script>
	<script src="./js/main.js"></script>
	<link href='assets/css/fullcalendar.css' rel='stylesheet' />
	<link href='assets/css/fullcalendar.print.css' rel='stylesheet' media='print' />
	<link rel="stylesheet" href="./css/index.css">
	<script src='assets/js/jquery-1.10.2.js' type="text/javascript"></script>
	<script src='assets/js/jquery-ui.custom.min.js' type="text/javascript"></script>
	<script src='assets/js/fullcalendar.js' type="text/javascript"></script>
	
	<style>

		body {

			text-align: center;
			font-size: 14px;
			font-family: "Helvetica Nueue", Arial, Verdana, sans-serif;
			background-color: #DDDDDD;
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

		#calendar {
			/*float: right; */
			margin: 0 auto;
			width: 900px;
			background-color: #FFFFFF;
			border-radius: 6px;
			box-shadow: 0 1px 2px #C3C3C3;
		}

		#mobile {
			display: none;
		}

		.list-group-item{
    		height:100px;
		}

		@media screen and (max-width:480px) {
			#calendar {
				display: none;

			}

			#mobile {
				display: block;
			}
			.i-title {
				font-size: 3rem;
				font-weight: bold;
				background-color: #eee;
			}
			.i-content {
				font-size: 2rem;
				line-height: 45px;
				font-weight: bold;
				
			}



		}
	</style>
	<script>
		$(document).ready(function() {
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
				editable: false,
				firstDay: 1, //  1(Monday) this can be changed to 0(Sunday) for the USA system
				selectable: false,
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
				selectHelper: false,
				droppable: false, // this allows things to be dropped onto the calendar !!!
				draggable: false,

				eventSources: [

					// your event source
					{
						url: '/api/fetch-event.php', // use the `url` property
						color: 'orange', // an option!
						textColor: 'black' // an option!
					}

					// any other sources...

				],


			})

		});
	</script>
</head>

<body>
	<div class="wrap">
		<!-- 메인 레이아웃 -->
		<!-- 이전에 있던 코드 -> 새로운 레이아웃으로 교체 -->
		<!-- 제대로된 css X (참고) -->
		<!-- 헤더 시작 -->
		<?php
		require_once("header.php");
		?>
		<!-- 헤더 끝 -->

		<!-- 메인 (캘린더) 시작 -->
		<section id="main">
			<div id='wrap'>

				<div id='calendar'></div>

				<div style='clear:both'></div>
			</div>
		</section>

		<div id="mobile">
			<ul class="list-group">
				<li class="list-group-item i-title">기능반 일정</li>
				<?php foreach($lists as $list) : ?>
				<li class="list-group-item"><span class="i-content"><?= $list->title ?></span> </br> <?= $list->start ?> ~ <?= $list->end ?></li>
				<?php endforeach; ?>
			</ul>

			<div class='paging_area'>
				<?php if ($start_page > 1) : ?>
					<a class='move_btn' href="<?= "./index.php?page=$prev_page" ?>">« 이전</a>
					<a class='pagenum' href="<?= "./index.php?page=1" ?>">1</a> ...
				<?php else : ?>
					<span class='move_btn disabled'>« 이전</span>
				<?php endif ?>

				<?php for ($p = $start_page; $p <= $end_page; $p++) : ?>
					<a class='pagenum <?= ($p == $page) ? "current" : "" ?>' href="<?= "./index.php?page=$p" ?>">
						<?= $p ?>
					</a>
				<?php endfor ?>

				<?php if ($end_page < $page_count) : ?>
					... <a class='pagenum' href="<?= "./index.php?page=$page_count" ?>"><?= $page_count ?></a>
					<a class='move_btn' href="<?= "./index.php?page=$next_page" ?>">다음 »</a>
				<?php else : ?>
					<span class='move_btn disabled'>다음 »</span>
				<?php endif ?>
			</div>
		</div>
		<!-- 푸터 시작 -->
		<footer>
			<div id="copyright">
				<div class="copyright-wrap">
					<span class="copyright-text">copyright ⓒ 2021 All rights reserved by YangYoung</span>
				</div>
			</div>
		</footer>
		<!-- 푸터 끝 -->
	</div>
</body>

</html>