<?php
require_once(__DIR__ .'/../services/ResponseService.php');
require_once(__DIR__ .'/../services/TraineeService.php');
require_once (__DIR__.'/../middleware.php');

class TraineeController {

    public static function autho($connection,$id)
    {
        // Check if ID is provided
        if (empty($id)) {
            echo ResponseService::error("We need the ID");
            exit;
        }
    
       
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
    
       
    }

    public function EntriesAndHabitsByText()
    {
        error_reporting(E_ERROR | E_PARSE);
      global $connection;
      $input = json_decode(file_get_contents("php://input"), true);
       
       if(!isset($input['id'])){
        echo ResponseService::error("where the user id");
       }
       $id = $input['id'] ;
       $this->autho($connection,$id);
        
       
        if ( empty($input["dayDate"]) || empty($input["text"])) {
            echo ResponseService::error("We need more data");
            return;
        }

       
        $dayDate = $input["dayDate"];
        $text = $input["text"];

    

  
        $response = TraineeService::take_data_from_text_to_database(
            $connection,
            $text,
            $dayDate,
            $id
         
        );

        if ($response ==="") {
            echo ResponseService::success("Excellent");
        } else {
            echo ResponseService::error($response);
        }
    }


    public function weeklySummary()
    {error_reporting(E_ERROR | E_PARSE);
    global $connection;
     $rawBody = file_get_contents("php://input");
    $body = json_decode($rawBody, true);

    if (!$body) {
        echo ResponseService::error("Invalid JSON");
        return;
    }
    $traineeId     = $body["id"];
    $dateTimeFrom  = $body["dateTimeFrom"] ?? date("Y-m-d 00:00:00", strtotime("-7 days"));
    $dateTimeTo    = $body["dateTimeTo"] ??  date("Y-m-d 23:59:59");
    $this->autho($connection,$traineeId);

    $result = TraineeService::GiveWeeklySummary($connection, $traineeId,$dateTimeFrom,$dateTimeTo);
    echo $result;
    }

    public function nutritionCoach()
    {error_reporting(E_ERROR | E_PARSE);
        global $connection;
    
       
    
        if (!isset($_GET["id"])) {
            echo ResponseService::error("Trainee ID is required");
            return;
        }
    
        $id = $_GET["id"];
         $this->autho($connection,$id);
    
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
        $id=$_POST['id'];
        $this->autho($connection,$id);

        if(empty($_POST['exercise_minutes'])&& empty($_POST['walk_minutes'])&& empty($_POST['steps'])&& empty($_POST['sleep_hour']) && empty($_POST['caffeine']) && empty($_POST['calories_intake'])){
            echo ResponseService::error("no data ");
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
            $_POST = $body; 
        }
    
        if (!isset($_POST['id']) || empty($_POST['id'])) {
            echo ResponseService::error("User ID is missing");
            exit;
        }
        $id=$_POST['id'];
        $this->autho($connection,$id);
       

        $mealData=[
            "user_id"  => isset($_POST['id']),
          "meals" => isset($_POST['>meals ']) ? (int)$_POST['meals '] : null,
          "datetime"     => isset($_POST['datetime']) ? $_POST['datetime'] : null,
          "meal_categories"            => isset($_POST['meal_categories']) ? (int)$_POST['meal_categories'] : null,
        ];

    

        $traineeMeal=new Meal($mealData);
        $result=TraineeService::addMealManual($connection,$traineeMeal);
        echo $result;
    }


    
    public function TakeSuggestionFromAi(){
        global $connection;
        if(!isset($_GET["id"])){
            echo  ResponseService::error("where your id  ");
        }
        $this->autho($connection,$id);
        if(!isset($_POST['text'])){
            echo ResponseService::error("there is error ");
            return;
        }

        $result=TraineeService::makeSuggestionByAI($connection,$text);
        echo $result;


    }
    
}
?>
