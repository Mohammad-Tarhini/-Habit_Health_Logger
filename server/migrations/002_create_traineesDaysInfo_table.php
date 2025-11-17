<?php
require_once("../connection/connection.php");

$sql="CREATE TABLE trainees_days_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    exercise_minutes int ,
    walk_minutes INT DEFAULT 0,
    steps INT DEFAULT 0,
    sleep_hour flaot,      -- allows 7.5 hours etc
    caffeine INT DEFAULT 0,         -- cups
    calories_intake INT DEFAULT 0,  -- calories consumed
    calories_burn INT DEFAULT 0,    -- calories burned
    day DATE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);"
$query = $connection->prepare($sql);
$query->execute();

echo "Table(s) Created!";



?>