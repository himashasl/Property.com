<?php
include '../../init.php';
extract($_POST);

$db = dbConn();
$sql = "DELETE FROM users WHERE UserId='$UserId'";
$db->query($sql);

header("Location:manage.php");
