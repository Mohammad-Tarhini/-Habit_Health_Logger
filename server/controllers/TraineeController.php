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
      global $connection;
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
    {
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
    {
        global $connection;
    
        $this->autho($connection);
    
        if (!isset($_GET["id"])) {
            echo ResponseService::error("Trainee ID is required");
            return;
        }
    
        $id = intval($_GET["id"]);
    
        echo TraineeService::getNutritionCoachCard($connection, $id);
    }

    public function addEntriesAndHabitsManual(){
        global $connection;
        $this->autho($connection);
        
        $dayData = [
          
          "user_id"          => (int)$_POST['id'] ,
          "exercise_minutes" => isset($_POST['exercise_minutes']) ? (int)$_POST['exercise_minutes'] : null,
          "walk_minutes"     => isset($_POST['walk_minutes']) ? (int)$_POST['walk_minutes'] : null,
          "steps"            => isset($_POST['steps']) ? (int)$_POST['steps'] : null,
          "sleep_hour"       => isset($_POST['sleep_hour']) ? (float)$_POST['sleep_hour'] : null,
          "caffeine"         => isset($_POST['caffeine']) ? (int)$_POST['caffeine'] : null,
          "calories_intake"  => isset($_POST['calories_intake']) ? (int)$_POST['calories_intake'] : null,
          "calories_burn"    => isset($_POST['calories_burn']) ? (int)$_POST['calories_burn'] : null,
          "day"              => isset($_POST['day']) ? $_POST['day'] : null
        ];

        $traineeDay = new TraineeDayInfo($dayData);
        $result=TraineeService::addHabitsManual($connection,$traineeDay);
        echo $result;
    }
    public function addMealsManual(){
        global  $connection;
        $this->autho($connection);

        $mealData=[
            "user_id"  => isset($_POST['id']),
          "meals" => isset($_POST['>meals ']) ? (int)$_POST['meals '] : null,
          "datetime"     => isset($_POST['datetime']) ? $_POST['datetime'] : null,
          "meal_categories"            => isset($_POST['meal_categories']) ? (int)$_POST['meal_categories'] : null,
        ];

        $traineeMeal=new Meal($mealData);
        $result=TrainService::addMealManual($connection,$traineeMeal);
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
