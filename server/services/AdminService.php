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
public static function deleteTrainee($connection,$traineeId){
$isTraineeFound=User::findById($connection,$traineeId);

if($isTraineeFound==null){
    return "the trainee id is wrong";
}
 $isde1=Model::deleteWhere($connection, "meals_log", "user_id", $traineeId);
 if(!$isde1){
    return "erorr";
 }
 $isde2= Model::deleteWhere($connection, "trainees_days_info", "user_id", $traineeId);
 if(!$isde2){
    return "erorr";
 }
if(User::deleteById($connection,$traineeId))
    return true;
return false;

}
 public static function deleteTraineeDayInfo($connection,$day,$traineeId){
 $isTraineeFound=User::findById($connection,$traineeId);
 if($isTraineeFound==null){
     return "the trainee id is wrong";
 }
 $existTraineeDaInfo=TraineeDayInfo::findAllWhere($connection,["user_id"=>$traineeId,"day"=>$day]);
 if(empty($existTraineeDaInfo)){
     return "no data to delete";
 
 if(existTraineeDaInfo->deleteByObject($connection)){
     return true;
 }
 return false
 }



}





?>