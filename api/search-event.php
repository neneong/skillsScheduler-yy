<?php
    require_once "../lib/DB.php";
    $id = $_POST['id'];

    $json = array();
    $sqlQuery = "SELECT * FROM schedule WHERE id = $id ORDER BY id";

    $result = DB::execute($sqlQuery);
    $eventArray = array();
    
    while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
        array_push($eventArray, $row);
    }
 

    echo json_encode($eventArray);
?>