<?php

require_once "DB.php";
require_once "Session.php";

function vaildation($datas){
    foreach($datas as $key=>$value){
        if(trim($value) == ""){
            back("누락된 항목이 있습니다.");
        }
    }
}


function session(){
    return new Session();
}

function user(){
    return session()->get("user", true, true);
}

function redirect($url, $msg){
    echo "<script>alert('" . $msg . "');</script>";
    echo "<script>window.location.href='" .$url . "';</script>";
    exit;
}

function back($msg){
    echo "<script>alert('" . $msg . "');</script>";
    echo "<script>window.history.back();</script>";
    exit;
}

function output($str){
    return nl2br(str_replace(' ', '&nbps;', htmlentities($str)));
}

function returnJSON($obj){
    //JSON_UNESCAPED_UNICODE => UTF-8 인코딩 방식
    echo json_encode($obj, JSON_UNESCAPED_UNICODE);
    exit;
}

function getCookie($name){
    return $_COOKIE["$name"];
}

function getFinalId($sel,$where){
    $sqlSel = "SELECT " . $sel .  " FROM " . $where . " ORDER BY id DESC LIMIT 1;";
    $a = DB::fetch($sqlSel)->$sel;
    if($a==false){
        return 1;
    }else{
        return $a;
    }
    
}