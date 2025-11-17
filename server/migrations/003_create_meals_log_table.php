<?php
require_once("../connection/connection.php");

$sql="CREATE TABLE trainees_detail_day_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    meals VARCHAR(50) NOT NULL,      -- breakfast, lunch, dinner, snack
    calories_intake INT DEFAULT 0,
    meal_categories varchar(300),
    datetime DATETIME,
    day date,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);"
$query = $connection->prepare($sql);
$query->execute();

echo "Table(s) Created!";



?>