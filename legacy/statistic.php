<?php
require_once("header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>연간 및 월간 통계</title>
    <link rel="stylesheet" href="./css/fontawesome-free-5.15.4-web/css/all.min.css">
    <link rel="stylesheet" href="./css/bootstrap-3.3.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/statistic.css">
    <script src="./js/main.js"></script>
    <script src='assets/js/jquery-1.10.2.js' type="text/javascript"></script>
    <script src='assets/js/jquery-ui.custom.min.js' type="text/javascript"></script>
    <script src='assets/js/fullcalendar.js' type="text/javascript"></script>
</head>

<body>


    <!-- 막대 그래프 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <canvas id="bar-chart"></canvas>
    <script>
        new Chart(document.getElementById("bar-chart"), {
            type: 'bar',
            data: {
                labels: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
                datasets: [{
                    label: '월간 휴가 빈도 수',
                    data: [12, 19, 3, 5, 2, 3, 12, 19, 3, 5, 2, 3],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'],
                    borderColor: [
                        'rgba(194, 194, 194 , 0.9)',
                        'rgba(194, 194, 194 , 0.9)',
                        'rgba(194, 194, 194 , 0.9)',
                        'rgba(194, 194, 194 , 0.9)',
                        'rgba(194, 194, 194 , 0.9)',
                        'rgba(194, 194, 194 , 0.9)',
                        'rgba(194, 194, 194 , 0.9)',
                        'rgba(194, 194, 194 , 0.9)',
                        'rgba(194, 194, 194 , 0.9)',
                        'rgba(194, 194, 194 , 0.9)',
                        'rgba(194, 194, 194 , 0.9)',
                        'rgba(194, 194, 194 , 0.9)',],

                    borderWidth: 1

                }]
            },
            options: {
                legend: { display: false },
                title: {
                    display: true,
                    text: '월 휴가 빈도수 변화'
                },
                scales: {
                    yAxes: [
                        {
                            ticks: {
                                beginAtZero: true
                            }
                        }
                    ]
                }
            }

        });
    </script>


<?php
require_once("footer.php")
?>

 



</body>
   
</html>