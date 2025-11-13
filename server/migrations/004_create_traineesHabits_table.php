<?php
require_once("../connection/connection.php");

$sql="CREATE TABLE trainees_habits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    habit VARCHAR(255) NOT NULL,
    entries VARCHAR(255),
   
);"
$query = $connection->prepare($sql);
$query->execute();

echo "Table(s) Created!";



?>