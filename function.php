<?php

function dataClean($data=null){
    return htmlspecialchars(stripslashes(trim($data)));
}

function dbConn(){
    $conn=new mysqli("localhost","root","", "property.com");

    if($conn->connect_error){
        die("Connection Faild :".$conn->error);
    }

    return $conn;
}


?>