<?php
require_once("../connection/connection.php");

$sql="CREATE TABLE trainees_detail_day_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    meal_name VARCHAR(50) NOT NULL,      -- breakfast, lunch, dinner, snack
    meal_items TEXT,                      -- JSON string or comma-separated items
    calories INT DEFAULT 0,
    meal_time DATETIME,
    log_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);"
$query = $connection->prepare($sql);
$query->execute();

echo "Table(s) Created!";



?>