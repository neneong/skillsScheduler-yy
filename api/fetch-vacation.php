<?php
    require_once "../lib/DB.php";

    $json = array();
    $sqlQuery = "SELECT * FROM vacation WHERE assign = 1 ORDER BY id";

    $result = DB::execute($sqlQuery);
    $eventArray = array();
    
    while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
        $row['title'] = "[" . $row['student'] . "] " . $row['title'];
        array_push($eventArray, $row);
    }
 

    echo json_encode($eventArray);
?>