<?php

require_once("header.php");
require_once("./lib/DB.php");
require_once("./lib/lib.php");
require_once("./lib/Session.php");

$date = date("Y-m-d", time());
$dateString = explode("-", $date);


if ($dateString[2] == 1 && user()->edit == 1) {
	$id = $_SESSION["user"]->id;
	$sql = "UPDATE `user` SET `edit` = 0 SET `vac_count` = vac_count+5 WHERE id = $id";
	DB::execute($sql);
}

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
	<link rel="stylesheet" href="./css/style.css">
	<script src="./js/main.js"></script>
	<link href='assets/css/fullcalendar.css' rel='stylesheet' />
	<link href='assets/css/fullcalendar.print.css' rel='stylesheet' media='print' />
	<script src='assets/js/jquery-1.10.2.js' type="text/javascript"></script>
	<script src='assets/js/jquery-ui.custom.min.js' type="text/javascript"></script>
	<script src='assets/js/fullcalendar.js' type="text/javascript"></script>
	<script src="./js/jquery.cookie-1.4.1.min.js"></script>

</head>
<?php
ini_set('display_errors', '0');

if (user()->grade > 3 || user()->grade == null) exit("잘못된 접근입니다.");

// 휴가 확인
$db = new DB();
$user_name = $_SESSION["user"]->name;
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
	$sql = "SELECT * FROM `vacation` v WHERE assign = 1 AND v.student = '$user_name' ORDER BY v.id DESC LIMIT $offset2, $LIST_SIZE";
	$lists = DB::fetchAll($sql);

	$sql2 = "SELECT * FROM schedule s ORDER BY s.id DESC LIMIT $offset, $LIST_SIZE;";
	$ans = DB::fetchAll($sql2);
} else {
	$sql = "SELECT * FROM `vacation` v WHERE assign = 1 AND v.student = '$user_name'";
	$lists = DB::fetchAll($sql);
	$sql2 = "SELECT * FROM schedule;";
	$ans = DB::fetchAll($sql2);
}
$sql3 = "SELECT * FROM vacation WHERE `assign` = 0 AND `student` = '$user_name'";
$res = DB::fetchAll($sql3);
?>
<style>
	.pagenum{
		font-size:17px;
		margin:0 4px;
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

	#lists{
		overflow-y:auto;
		overflow-x:hidden;
	}

	.lists-title{
		width:192px;
		text-align:center;
	}

	.lists-content{
		width:192px;
		text-align:center;
	}

	.modal{
		margin-top:0;
		width:100%;
		height:100%;
		background-color: rgba(0, 0, 0, 0.493);
	}

	.modal>.content{
		margin-top:12%;
		width:20%;
		height:500px;
		margin-left:40%;
		background-color:white;
		border-radius: 20px;
		padding-top:20px;
	}

	.accept, .decline{
		margin-top:40px;
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
		margin: -15px auto;
		width: 900px;
		background-color: #FFFFFF;
		border-radius: 6px;
		box-shadow: 0 1px 2px #C3C3C3;

	}

	.current{
		border:1px solid;
		padding-left: 3px;
	}

	#mobile {
		display: none;
	}

	a:active,
	.vacation-btn:hover {
		outline: 0;
		text-decoration: none;
		color: white;
	}

	footer {
		position: absolute;
		bottom: -12%;
	}

	@media screen and (max-width:1500px) {
		#calendar {
			transform: translateX(-5%);
		}

	}

	@media screen and (max-width:1202px) {
		#calendar {
			transform: translateX(-10%);
		}

	}

	@media screen and (max-width:1100px) {
		#calendar {
			transform: translateX(-15%);
		}

	}

	@media screen and (max-width:480px) {

		#calendar {
			display: none;

		}

		#right-sidebar {
			width: 100%;
			position: relative;
			height: 400px;
		}

		#mobile {
			display: block;
		}

		.header-name {
			position: relative;
			left: -1%;
		}

		.T-title {
			font-size: 2rem;
			font-weight: bold;
			background-color: lightgray;
		}

		footer {
			margin-top: 20px;
			position: relative;
		}



	}
</style>
<script>
	const offset = new Date().getTimezoneOffset() * 60000;
	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();
	var id = <?php echo $_SESSION["user"]->id ?>;

	function get_vac_count() {
		$.ajax({
				url: '/api/get-vac-count.php',
				data: 'id=' + id,
				type: "POST",
				success: function(data) {
					console.log("success!");
				}
			})
			.done(function(json) {
				var obj = JSON.parse(json)
				vac_count = obj[0].vac_count.toString();
				let count = document.getElementById("count_vac")
				count.innerHTML = vac_count;
				console.log("SUCCESS")
			})

	}

	$(document).ready(function() {
		$(() => {
			get_vac_count();
			setInterval(get_vac_count, 5000)
		})
		$(() => {
			$('.modal2-x').click(function() {
				$(`.modal-details`).fadeToggle();
			});
		})

		$(() => {
			$('.ok').click(function() {
				$(`.modal-details`).fadeToggle();
			});
		})

		$(() => {
			$('.accept').click(() => {
				student = $.cookie('name');

				title = document.getElementById("tit-text").value

				str = document.getElementById("start-date");
				strs = new Date(str.value)
				strs = strs.toISOString()

				end = document.getElementById("end-date");
				var ends = new Date(end.value)
				ends = ends.toISOString()

				var grade = getCookie("grade")
				var day = ((new Date(ends).getTime() - new Date(strs).getTime()) / (1000 * 60 * 60 * 24));

				console.log(day)

				if (ends >= strs) {
					$.ajax({
							url: '/api/get-vac-count.php',
							data: 'id=' + id,
							type: "POST",
							success: function(data) {
								console.log("success!");
							}
						})

						.done(function(json) {
							var obj = JSON.parse(json)
							vac_count = obj[0].vac_count;
							if (vac_count > day) {
								$.ajax({
									url: '/api/add-vacation.php',
									data: 'student=' + student + '&title=' + title + '&start=' + strs + '&end=' + ends,
									type: "POST",
									success: function(data) {
										console.log("success!");
										$(".modal").fadeToggle();

										tit = document.getElementById("tit-text")
										str = document.getElementById("start-date");
										end = document.getElementById("end-date");

										tit.value = " ";
										str.value = "";
										end.value = "";
										location.reload();
									}
								});
							} else {
								alert("허용 휴가기간보다 길어요!")
							}
						});
				} else {
					alert("종료 날짜는 시작 날짜보다 뒤여야만 합니다!")
				}

			});

		})
		$(() => {
			$('.cancel').click(() => {
				$(".modal").fadeToggle();
				str = document.getElementById("start-date");
				end = document.getElementById("end-date");
				content = document.getElementById("reason-text");
				str.value = "";
				end.value = "";
				content.value = "";
			});
		})


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
			editable: false,
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
			select: function(start, end) {
				$(".modal").fadeToggle();
				starts = new Date(start - offset).toISOString().slice(0, 10);
				str = document.getElementById("start-date");
				str.value = starts;

				ends = new Date(end - offset).toISOString().slice(0, 10);
				ending = document.getElementById("end-date");
				ending.value = ends;

				calendar.fullCalendar('unselect');
			},
			eventDrop: function(event, delta) {


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
					textColor: 'black', // an option!
					editable: false
				},

				{
					url: '/api/fetch-vacation.php', // use the `url` property
					color: 'rgb(255,108,112)', // an option!
					textColor: 'black', // an option!
					editable: false
					// any other sources...
				}
			],


		})

	});
</script>

<body>
	<div id="mobile">
		<ul class="list-group">
			<li class="list-group-item T-title">-기능반 일정-</li>
			<?php foreach ($ans as $an) : ?>
				<li class="list-group-item"><?= $an->title ?></li>
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


			<li class="list-group-item T-title">-개인 휴가 일정-</li>
			<?php foreach ($lists as $list) : ?>
				<li class="list-group-item"><?= $list->title ?></li>
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
	<div class="wrap">
		<div class="content">
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
						<?php if ($lists == $user_name) : ?>
							<a href="./Data.php" class="vacation-btn"><?= $lists->title ?></a>
						<?php else : ?>
							<a href="./Data.php" class="vacation-btn">잔여 횟수 : <span id="count_vac" style="color:white;"> </span></a>
						<?php endif; ?>
					</div>
				</div>

				<div id="vacation">
					<div class="vacation-wrap">
						<span class="leftover">승인 대기중인 휴가</span>
					</div>
				</div>
				<div id="lists table-responsive project-list">
					<table class="table table-striped table-centered table-nowrap" width="100%" cellspacing="0" cellpadding="0" style="text-align:center;">
						<thead>
							<tr>
								<th class = "lists-title">사유</th>
								<th class = "lists-title">일수</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($res as $re) : ?>
								<tr>

									<td class = "lists-content"><?= $re->title ?></td>
									<td class = "lists-content">
										<?php
										$start = $re->start;
										$end = $re->end;
										$start = new DateTime($start);
										$end = new DateTime($end);

										$a = date_diff($end, $start);
										echo $a->d + 1 . "일";
										?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</section>
			<!-- 오른쪽 탭 끝 -->
		</div>

	</div>

	<div class="modal">
		<div class="content">
			<h3 class="title">기능반 휴가</h3>
			<div class="name">
				<h4>이름</h4>
				<p><?php echo $_SESSION['user']->name; ?></p>
			</div>
			<div class="tit">
				<h4>사유</h4>
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
			<button class="accept">확인</button>
			<button class="cancel">닫기</button>
		</div>
	</div>

	<div class="modal-details">
		<div class="content">
			<h3 class="title" id="detail-h">기능반</h3>
			<div class="name">
				<h4 id="name-title">이름</h4>
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

			<button class="ok">확인하기</button>
			<button class="modal2-x">닫기</button>
		</div>
	</div>
</body>


</html>

<?php
require_once("footer.php");
?>