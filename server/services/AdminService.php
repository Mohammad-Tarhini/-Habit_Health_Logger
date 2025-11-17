<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/TraineeDayInfo.php';

class AdminService{

public static function GetAllTrainees($connection)
{
    $getTrainees = User::findByColumn($connection, "role", "trainee");

    if (empty($getTrainees)) {
        return null;
    }

    $array_of_trainees_to_send_to_client = [];

    foreach ($getTrainees as $trainee) {
        $array_of_trainees_to_send_to_client[] = [
            "id"    => $trainee->getID(),
            "name"  => $trainee->getName(),
            "email" => $trainee->getEmail()
        ];
    }

    return $array_of_trainees_to_send_to_client;
}

public static function GetTraineeInfoDays(mysqli $connection, int $traineeId)
{
    $traineeDays = TraineeDayInfo::findByColumn($connection, "user_id", $traineeId);

    if (empty($traineeDays)) {
        return null;
    }

    $trainee_info_to_send_to_admin = [];

    foreach ($traineeDays as $info) {
          $trainee_info_to_send_to_admin[] = [
                "id"          => $info->getId(),
                "day_date"    => $info->getDay(),
                "walk_minutes"=> $info->getWalkMinutes(),
                "walk_steps"  => $info->getSteps(),
                "sleep_hours" => $info->getSleepHour(),
                "caffeine"    => $info->getCaffeine(),
                "calories_eaten"=> $info->getCaloriesIntake(),
                "calories_burned"=> $info->getCaloriesBurn()
            ];
    }

    return $trainee_info_to_send_to_admin;
}


}





?>