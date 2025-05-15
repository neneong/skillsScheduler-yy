<?php
require_once "../lib/DB.php";
require_once "../lib/lib.php";

$id = isset($_POST['id']) ? $_POST['id'] : "";
$assign = isset($_POST['assign']) ? $_POST['assign'] : "";

$sqlInsert = "UPDATE `vacation` SET assign = $assign WHERE id = $id";

echo($sqlInsert);

DB::execute($sqlInsert);

if($assign == 1){
    $sqlSelect = "SELECT * FROM `vacation` WHERE id = $id";

    $res = DB::fetchAll($sqlSelect);

    foreach($res as $re){
        $name = $re->student;

        $start = $re->start;

        $end = $re->end;
    }

    $starts = new DateTime($start);

    $ends = new DateTime($end);

    $count = date_diff($ends,$starts);

    $count = $count->d;

    $count = $count + 1;

    var_dump($count);

    $sqlUpdate = "UPDATE `user` SET vac_count = vac_count-$count WHERE name = '$name'";

    DB::execute($sqlUpdate);

    
}

?>