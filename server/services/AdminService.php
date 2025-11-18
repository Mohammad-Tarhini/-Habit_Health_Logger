<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/TraineeDayInfo.php';
require_once __DIR__ . '/../models/Model.php';

class AdminService
{
    // -------------------------------------------------------
    // GET ALL TRAINEES
    // -------------------------------------------------------
    public static function GetAllTrainees($connection)
    {
        $getTrainees = User::findByColumn($connection, "role", "trainee");

        if (empty($getTrainees)) {
            return null;
        }

        $array = [];

        foreach ($getTrainees as $trainee) {
            $array[] = [
                "id"    => $trainee->getID(),
                "name"  => $trainee->getName(),
                "email" => $trainee->getEmail()
            ];
        }

        return $array;
    }

    // -------------------------------------------------------
    // GET ALL DAY INFO FOR ONE TRAINEE
    // -------------------------------------------------------
    public static function GetTraineeInfoDays(mysqli $connection, int $traineeId)
    {
        $traineeDays = TraineeDayInfo::findByColumn($connection, "user_id", $traineeId);

        if (empty($traineeDays)) {
            return "no data";
        }

        $array = [];

        foreach ($traineeDays as $info) {
            $array[] = [
                "id"               => $info->getId(),
                "day_date"         => $info->getDay(),
                "walk_minutes"     => $info->getWalkMinutes(),
                "walk_steps"       => $info->getSteps(),
                "sleep_hours"      => $info->getSleepHour(),
                "caffeine"         => $info->getCaffeine(),
                "calories_eaten"   => $info->getCaloriesIntake(),
                "calories_burned"  => $info->getCaloriesBurn()
            ];
        }

        return $array;
    }

    // -------------------------------------------------------
    // DELETE TRAINEE + ALL RELATED DATA
    // -------------------------------------------------------
    public static function deleteTrainee($connection, $traineeId)
    {
        $isTraineeFound = User::findById($connection, $traineeId);

        if ($isTraineeFound == null) {
            return "The trainee ID is wrong";
        }

        // Delete meals
        $isde1 = Model::deleteWhere($connection, "meals_log", "user_id", $traineeId);
        if (!$isde1) {
            return "Error deleting meals";
        }

        // Delete trainee day info
        $isde2 = Model::deleteWhere($connection, "trainees_days_info", "user_id", $traineeId);
        if (!$isde2) {
            return "Error deleting trainee day info";
        }

        // Delete user
        if (User::deleteById($connection, $traineeId)) {
            return true;
        }

        return false;
    }

    // -------------------------------------------------------
    // DELETE ONE DAY RECORD FOR TRAINEE
    // -------------------------------------------------------
    public static function deleteTraineeDayInfo($connection, $dayId, $traineeId)
    {
        // Check trainee exists
        $isTraineeFound = User::findById($connection, $traineeId);
        if ($isTraineeFound == null) {
            return "The trainee ID is wrong";
        }

        // Check day info exists
        $existTraineeDayInfo = TraineeDayInfo::findById($connection, $dayId);
        if (empty($existTraineeDayInfo)) {
            return "No data to delete";
        }

        // Delete object
        if ($existTraineeDayInfo->deleteByObject($connection)) {
            return true;
        }

        return false;
    }
}
?>
