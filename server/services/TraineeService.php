<?php
require_once (__DIR__ . '/../controllers/TraineeController.php');
require_once (__DIR__.'/../models/TraineeDayInfo.php');
require_once (__DIR__ . '/../models/Meal.php');
require_once(__DIR__ . '/../services/ResponseService.php');
require_once(__DIR__.'/../ExternalService/TextReview.php');
require_once(__DIR__.'/../ExternalService/GenerateAiSummary.php');


class TraineeService{

public static function take_data_from_text_to_database(mysqli $connection, $text, $day, $userid)
{
    if (empty($text) || empty($day)) {
        return "Please enter text or date";
    }

    // 1) AI extract data
    $daysInfo = TextReview::reviewTextGenenal($text);
    $mealInfo = TextReview::reviewTextforMeals($text);
    if(!is_array($mealInfo) && !is_array($daysInfo)  ){
        return "AI General extraction error: " . $daysInfo ." ".$mealInfo;
    }
    if ((isset($daysInfo['error']) && isset($mealInfo['error']))){
         return "AI General extraction error: " . $daysInfo['error'] .",". $mealInfo['error'];
    }
    

    // 2) Get existing day record
    //$existingDay = TraineeDayInfo::findAllWhere($connection, ["day" => $day, "user_id" => $userid]);
    

    // 3) SAVE DAILY GENERAL INFO
    if (! empty($daysInfo) || !isset($daysInfo['error'])) {
        
           
            $daysInfo["day"] = $day;
            $daysInfo["user_id"] = $userid;

    // cast to correct types
             $daysInfo["exercise_minutes"] = isset($daysInfo["exercise_minutes"]) ? (int)$daysInfo["exercise_minutes"] : null;
             $daysInfo["walk_minutes"]     = isset($daysInfo["walk_minutes"]) ? (int)$daysInfo["walk_minutes"] : null;
             $daysInfo["steps"]            = isset($daysInfo["steps"]) ? (int)$daysInfo["steps"] : null;
             $daysInfo["sleep_hour"]       = isset($daysInfo["sleep_hour"]) ? (float)$daysInfo["sleep_hour"] : null;
             $daysInfo["caffeine"]         = isset($daysInfo["caffeine"]) ? (int)$daysInfo["caffeine"] : null;
             $daysInfo["calories_intake"]  = isset($daysInfo["calories_intake"]) ? (int)$daysInfo["calories_intake"] : null;
             $daysInfo["calories_burn"]    = isset($daysInfo["calories_burn"]) ? (int)$daysInfo["calories_burn"] : null;


            $daysObj = new TraineeDayInfo($daysInfo);
            $result=$daysObj->insert($connection);
            if($result===false) return "cannot insert";
            if(empty($result)) return "error in insertation ";
            if(!is_int($result))return "error on add data ";

        //  else {
        //     $dayObj = $existingDay[0];

        //     if (isset($daysInfo['exercise_minutes'])) $dayObj->setExerciseMinutes((int)$daysInfo['exercise_minutes'] +(int) $dayObj->getExerciseMinutes());
        //     if (isset($daysInfo['walk_minutes'])) $dayObj->setWalkMinutes((int)$daysInfo['walk_minutes'] +(int) $dayObj->getWalkMinutes());
        //     if (isset($daysInfo['steps'])) $dayObj->setSteps((int)$daysInfo['steps'] + (int)$dayObj->getSteps());
        //     if (isset($daysInfo['sleep_hour'])) $dayObj->setSleepHour((float)$daysInfo['sleep_hour'] );
        //     if (isset($daysInfo['caffeine'])) $dayObj->setCaffeine((int)$daysInfo['caffeine'] + (int)$dayObj->getCaffeine());
        //     if (isset($daysInfo['calories_intake'])) $dayObj->setCaloriesIntake((int)$daysInfo['calories_intake'] + (int)$dayObj->getCaloriesIntake());
        //     if (isset($daysInfo['calories_burn'])) $dayObj->setCaloriesBurn((int)$daysInfo['calories_burn'] + (int)$dayObj->getCaloriesBurn());
            
        //     $result = $dayObj->update($connection);
        //     if ($result === false) return "Cannot update day info";
        //     if(empty($result)) return "uihsduhufh";
            
        // }
    }

         
    // 4) SAVE MEALS INFO
    
    if (!empty($mealInfo)  && !isset($mealInfo['error'])) {
        foreach ($mealInfo as $mealItem) {
        // Make sure each item is an array
        if (!is_array($mealItem)) continue;
    
        $meal = new Meal([
            "meals" => $mealItem["meals"] ?? null,
            "datetime" => $mealItem["datetime"] ?? null,
            "meal_categories" => $mealItem["meal_categories"] ?? null,
            "calories_intake" => $mealItem["calories_intake"] ?? null,
            "user_id" => $userid
        ]);

    $result = $meal->insert($connection);

    if ($result === false) return "Cannot insert meal";
}
    }

    return "";
}


public static function GiveWeeklySummary(mysqli $connection,$userId){
     $endDate = date('Y-m-d'); // today
     $startDate = date('Y-m-d', strtotime('-7 days')); // 7 days ago
    
     
     $weekData = TraineeDayInfo::findByDateRange($connection, "day", $startDate, $endDate, $userId);
     
    if(empty($weekData)){
        return "no data";
    }
    $jsonReady = [];
    foreach ($weekData as $day) {
        $jsonReady[] = [
            "day_date"   => $day->day_date,
            "exercises"  => $day->exercises,
            "meals"      => $day->meals,
            "calories"   => $day->calories ?? null,
            "sleep"      => $day->sleep ?? null
        ];
    }
   $summaryResult = AiService::summarizeJsonText($jsonReady);

    if (!$summaryResult["success"]) {
        return ResponseService::error($summaryResult["error"]);
    }
    
    return ResponseService::success($summaryResult["summary"]);

}

public static function getNutritionCoachCard($connection, int $traineeId){
    $endDate = date('Y-m-d'); // today
    $startDate = date('Y-m-d', strtotime('-7 days')); // 7 days ago
    $previosDaysData = TraineeDayInfo::findByDateRange($connection, "day", $startDate, $endDate, $traineeId);
    $todayMeals = Meal::findByDateRange($connection, "datetime", $endDate." 00:00:00", $endDate." 23:59:59", $traineeId);
    if (empty($previosDaysData)) {
        return ResponseService::success("No data to help you");
    }
    $mealArray = [];
    foreach ($todayMeals as $meal) {
        $mealArray[] = [
            "meals"          => $meal->meals,
            "datetime"       => $meal->datetime,
            "meal_categories"=> $meal->meal_categories,
            "calories_intake"=> $meal->calories_intake
        ];}

    $jsonReady = [];
    foreach ($weekData as $day) {
        $jsonReady[] = [
            "day_date"   => $day->day_date,
            "exercises"  => $day->exercises,
            "meals"      => $day->meals,
            "calories"   => $day->calories ?? null,
            "sleep"      => $day->sleep ?? null
        ];
    }
    $coach = AiService::nutritionCoachCard($mealArray,$jsonReady);
    if (!$coach["success"]) {
        return ResponseService::error($coach["error"]);
    }

    return ResponseService::success($coach["summary"]);



}



public static function addHabitsManual(mysqli $connection, TraineeDayInfo $habitsdays)
{
    if (empty($habitsdays)) {
        return ResponseService::error("No data to add");
    }

    
    $result = $habitsdays->insert($connection);

    if (!is_int($result)) {
        return ResponseService::error("Error while inserting data");
    }

    return ResponseService::success("Habits added successfully");
}

public static function addMealManual(mysqli $connection, Meal $meal)
{
    if (empty($meal)) {
        return ResponseService::error("No meal data to add");
    }

    $result = $meal->insert($connection);

    if (!is_int($result)) {
        return ResponseService::error("An error occurred while inserting meal");
    }

    return ResponseService::success("Meal added successfully");
}
public static function makeSuggestionByAI(mysqli $connection, $text)
{
    if (!isset($text) || trim($text) === "") {
        return ResponseService::error("No text provided");
    }

    $result = weeklyAiSummary::RecieveTextAndsendTextincludesugestion($text);

    if (!$result["success"]) {
        return ResponseService::error("Error while generating AI suggestion");
    }

    return ResponseService::success($result["summary"]);
}

}



?>