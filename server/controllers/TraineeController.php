<?php
require_once(__DIR__ . '/../services/ResponseService.php');
require_once(__DIR__ . '/../services/TraineeService.php');
require_once(__DIR__ . '/../middleware.php');

class TraineeController {

    private static function autho($connection, $id)
    {
        if (empty($id)) {
            echo ResponseService::error("User ID missing");
            exit;
        }

        $role = Middleware::Authorization($connection, $id);

        if (!$role) {
            echo ResponseService::error("Unauthorized user");
            exit;
        }

        if ($role !== "trainee") {
            echo ResponseService::error("User is not a trainee");
            exit;
        }
    }


    // ---------------- TEXT ENTRIES ----------------
    public function EntriesAndHabitsByText()
    {
        global $connection;

        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['id'], $input['dayDate'], $input['text'])) {
            echo ResponseService::error("Missing required fields");
            return;
        }

        $id = $input['id'];
        self::autho($connection, $id);

        $resp = TraineeService::take_data_from_text_to_database(
            $connection,
            $input["text"],
            $input["dayDate"],
            $id
        );

        echo $resp === "" ? ResponseService::success("Excellent") : ResponseService::error($resp);
    }


    // ---------------- WEEKLY SUMMARY ----------------
    public function weeklySummary()
    {
        error_reporting(E_ERROR | E_PARSE);
        global $connection;
    
        $body = json_decode(file_get_contents("php://input"), true);
    
        if (!$body || !isset($body["id"])) {
            echo ResponseService::error("Invalid request");
            return;
        }
    
        $id = $body["id"];
        self::autho($connection, $id);
    
        $fromStr = isset($body["dateTimeFrom"]) ? $body["dateTimeFrom"] : date("Y-m-d 00:00:00", strtotime("-7 days"));
        $toStr   = isset($body["dateTimeTo"])   ? $body["dateTimeTo"]   : date("Y-m-d 23:59:59");
    
        // Convert to DateTime safely
        // $from = DateTime::createFromFormat('Y-m-d H:i:s', $fromStr) ?: new DateTime($fromStr);
        // $to   = DateTime::createFromFormat('Y-m-d H:i:s', $toStr)   ?: new DateTime($toStr);
    
        echo TraineeService::GiveWeeklySummary($connection, $id, $fromStr, $toStr);
    }

    // ---------------- NUTRITION COACH ----------------
    public function nutritionCoach()
    {error_reporting(E_ERROR | E_PARSE);
        global $connection;

        if (!isset($_GET["id"])) {
            echo ResponseService::error("Trainee ID is required");
            return;
        }

        $id = $_GET["id"];
        self::autho($connection, $id);

        echo TraineeService::getNutritionCoachCard($connection, $id);
    }


    // ---------------- ADD HABITS MANUAL ----------------
    public function addEntriesAndHabitsManual()
    {
        global $connection;

        $body = json_decode(file_get_contents("php://input"), true);
        $_POST = $body ?? [];

        if (!isset($_POST["id"])) {
            echo ResponseService::error("Missing ID");
            return;
        }

        $id = $_POST["id"];
        self::autho($connection, $id);

      $dayData = [
          "user_id"          => (int)$id,
          "exercise_minutes" => !empty($_POST['exercise_minutes']) ? (int)$_POST['exercise_minutes'] : null,
          "walk_minutes"     => !empty($_POST['walk_minutes']) ? (int)$_POST['walk_minutes'] : null,
          "steps"            => !empty($_POST['steps']) ? (int)$_POST['steps'] : null,
          "sleep_hour"       => !empty($_POST['sleep_hour']) ? (float)$_POST['sleep_hour'] : null,
          "caffeine"         => !empty($_POST['caffeine']) ? (int)$_POST['caffeine'] : null,
          "calories_intake"  => !empty($_POST['calories_intake']) ? (int)$_POST['calories_intake'] : null,
          "calories_burn"    => !empty($_POST['calories_burn']) ? (int)$_POST['calories_burn'] : null,
          "day"              => !empty($_POST['day']) ? $_POST['day'] : null
      ];
         $allEmpty = true;
       foreach ($dayData as $key => $value) {
           if ($key === 'user_id') continue; // skip user_id
        if (!is_null($value) && $value !== '') {
            $allEmpty = false;
            break;
        }
    }
    
    if ($allEmpty) {
        echo ResponseService::error("No data provided");
        exit;
    }
            $obj = new TraineeDayInfo($dayData);
            echo TraineeService::addHabitsManual($connection, $obj);
        }
    
    
    // ---------------- ADD MEALS ----------------
    public function addMealsManual()
    {
        global $connection;

        $body = json_decode(file_get_contents("php://input"), true);
        $_POST = $body ?? [];

        if (!isset($_POST["id"])) {
            echo ResponseService::error("Missing ID");
            return;
        }

        $id = $_POST["id"];
        self::autho($connection, $id);

        $mealData = [
            "user_id"         => $id,
            "meals"           => !empty($_POST["meals"]) ? $_POST["meals"] : null,
            "datetime"        => !empty($_POST["datetime"]) ? $_POST["datetime"] : null,
            "meal_categories" => !empty($_POST["meal_categories"]) ? $_POST["meal_categories"] : null
        ];
        
        // Check if all data (except user_id) is empty
        $allEmpty = true;
        foreach ($mealData as $key => $value) {
            if ($key === 'user_id') continue;
            if (!is_null($value)) {
                $allEmpty = false;
                break;
            }
        }
        
        if ($allEmpty) {
            echo ResponseService::error("No meal data provided");
            return;
        }
        
        $meal = new Meal($mealData);
        echo TraineeService::addMealManual($connection, $meal);
    }


    // ---------------- AI SUGGESTION ----------------
    public function TakeSuggestionFromAi()
    {
        global $connection;

        $body = json_decode(file_get_contents("php://input"), true);
        $_POST = $body ?? [];

        if (!isset($_POST["id"], $_POST["text"])) {
            echo ResponseService::error("Missing parameters");
            return;
        }

        $id = $_POST["id"];
        $text = $_POST["text"];

        self::autho($connection, $id);

        echo TraineeService::makeSuggestionByAI($connection, $text);
    }
}
