<?php
require_once(__DIR__ .'/../services/ResponseService.php');
require_once(__DIR__ .'/../services/TraineeService.php');
require_once (__DIR__.'/../middleware.php');

class TraineeController {

    public static function autho($connection)
    {
        // Check if ID is provided
        if (empty($_GET['id'])) {
            echo ResponseService::error("We need the ID");
            exit;
        }
    
        $id = (int)$_GET['id']; // get the user ID
    
        // Authorization: pass both $connection and $id
        $Autho = Middleware::Authorization($connection, $id);
    
        if (!$Autho) {
            echo ResponseService::error("The user is not authorized");
            exit;
        }
    
        if ($Autho !== "trainee") {
            echo ResponseService::error("The user is not a trainee");
            exit;
        }
    
        return $id;
    }

    public function EntriesAndHabitsByText()
    {
        error_reporting(E_ERROR | E_PARSE);
      global $connection;
      $input = json_decode(file_get_contents("php://input"), true);
       $id = $input['id'] ?? null; 
      $id=$this->autho($connection);
        
        // --------------------
        // Validate Inputs
        // --------------------
        if ( empty($_GET["dayDate"]) || empty($_GET["text"])) {
            echo ResponseService::error("We need more data");
            return;
        }

       
        $dayDate = $_GET["dayDate"];
        $text = $_GET["text"];

        global $connection;

       
        // --------------------
        // Execute service
        // --------------------
        $response = TraineeService::take_data_from_text_to_database(
            $connection,
            $text,
            $dayDate,
            $id
         
        );

        // --------------------
        // Result
        // --------------------
        if ($response ==="") {
            echo ResponseService::success("Excellent");
        } else {
            echo ResponseService::error($response);
        }
    }
    public function weeklySummary()
    {error_reporting(E_ERROR | E_PARSE);
    global $connection;
    $this->autho($connection);
    if (!isset($_GET["id"])) {
        echo ResponseService::error("Trainee ID required");
        return;
    }
    $traineeId = intval($_GET["id"]);
    $result = TraineeService::GiveWeeklySummary($connection, $traineeId);
    echo $result;
    }

    public function nutritionCoach()
    {error_reporting(E_ERROR | E_PARSE);
        global $connection;
    
        $this->autho($connection);
    
        if (!isset($_GET["id"])) {
            echo ResponseService::error("Trainee ID is required");
            return;
        }
    
        $id = intval($_GET["id"]);
    
        echo TraineeService::getNutritionCoachCard($connection, $id);
    }

    public function addEntriesAndHabitsManual() {
        error_reporting(E_ERROR | E_PARSE);
        global $connection;
    
        // Read JSON from axios
        $body = json_decode(file_get_contents("php://input"), true);
        if ($body) {
            $_POST = $body; // Convert to POST
        }
    
        if (!isset($_POST['id']) || empty($_POST['id'])) {
            echo ResponseService::error("User ID is missing");
            exit;
        }
    
        $dayData = [
            "user_id"          => (int)$_POST['id'],
            "exercise_minutes" => isset($_POST['exercise_minutes']) ? (int)$_POST['exercise_minutes'] : null,
            "walk_minutes"     => isset($_POST['walk_minutes']) ? (int)$_POST['walk_minutes'] : null,
            "steps"            => isset($_POST['steps']) ? (int)$_POST['steps'] : null,
            "sleep_hour"       => isset($_POST['sleep_hour']) ? (float)$_POST['sleep_hour'] : null,
            "caffeine"         => isset($_POST['caffeine']) ? (int)$_POST['caffeine'] : null,
            "calories_intake"  => isset($_POST['calories_intake']) ? (int)$_POST['calories_intake'] : null,
            "calories_burn"    => isset($_POST['calories_burn']) ? (int)$_POST['calories_burn'] : null,
            "day"              => isset($_POST['day']) ? $_POST['day'] : null
        ];
    
        $Autho = Middleware::Authorization($connection, $dayData["user_id"]);
    
        if (!$Autho) {
            echo ResponseService::error("The user is not authorized");
            exit;
        }
    
        if ($Autho !== "trainee") {
            echo ResponseService::error("The user is not a trainee");
            exit;
        }
    
        $traineeDay = new TraineeDayInfo($dayData);
        $result = TraineeService::addHabitsManual($connection, $traineeDay);
    
        echo $result;
    }

    public function addMealsManual(){
        error_reporting(E_ERROR | E_PARSE);
        global $connection;
    
        // Read JSON from axios
        $body = json_decode(file_get_contents("php://input"), true);
        if ($body) {
            $_POST = $body; // Convert to POST
        }
    
        if (!isset($_POST['id']) || empty($_POST['id'])) {
            echo ResponseService::error("User ID is missing");
            exit;
        }

        $mealData=[
            "user_id"  => isset($_POST['id']),
          "meals" => isset($_POST['>meals ']) ? (int)$_POST['meals '] : null,
          "datetime"     => isset($_POST['datetime']) ? $_POST['datetime'] : null,
          "meal_categories"            => isset($_POST['meal_categories']) ? (int)$_POST['meal_categories'] : null,
        ];
        $Autho = Middleware::Authorization($connection, $mealData["user_id"]);
    
        if (!$Autho) {
            echo ResponseService::error("The user is not authorized");
            exit;
        }
    
        if ($Autho !== "trainee") {
            echo ResponseService::error("The user is not a trainee");
            exit;
        }
    

        $traineeMeal=new Meal($mealData);
        $result=TraineeService::addMealManual($connection,$traineeMeal);
        echo $result;


    }
    public function TakeSuggestionFromAi(){
        global $connection;
        $this->autho($connection);
        if(!isset($_POST['text'])){
            echo ResponseService::error("there is error ");
            return;
        }

        $result=TraineeService::makeSuggestionByAI($connection,$text);
        echo $result;


    }
    
}
?>
