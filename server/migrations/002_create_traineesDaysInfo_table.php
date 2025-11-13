<?php
require_once("../connection/connection.php");

$sql="CREATE TABLE trainees_days_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    walk_minutes INT DEFAULT 0,
    steps INT DEFAULT 0,
    sleep_hours time,      -- allows 7.5 hours etc
    wakeup_hour TIME,
    caffeine INT DEFAULT 0,         -- cups
    calories_intake INT DEFAULT 0,  -- calories consumed
    calories_burn INT DEFAULT 0,    -- calories burned
    log_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);"
$query = $connection->prepare($sql);
$query->execute();

echo "Table(s) Created!";



?>