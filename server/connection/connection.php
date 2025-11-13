<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

$connection=new mysqli("localhost","root","","health_logger_db");

if($connection ->connection_error){
    die("connection error" . $connection-> connection_error)
}


?>