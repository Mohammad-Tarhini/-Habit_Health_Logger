<?php
require_once(__DIR__ .'/../models/User.php');
require_once(__DIR__ .'/../connection/connection.php');
require_once(__DIR__ .'/../services/ResponseService.php');
require_once(__DIR__.'/../services/AdminService.php');
require_once(__DIR__.'/../middleware.php');

class AdminController{

    public static function autho($connection,$id)
    {
 
    
        // Authorization
        $Autho = Middleware::Authorization($connection,$id);
    
        if (!$Autho) {
            echo ResponseService::error($Autho);
            exit;
        }
    
        if ($Autho !== "admin") {
            echo ResponseService::error("The user is not an admin");
            exit;
        }
        

        // Authorized => continue
    }

    

    public function getAllTraineesForAdmin(){
        error_reporting(E_ERROR | E_PARSE);
        global $connection;
        // Check if ID is provided
        if (empty($_GET['id'])) {
            echo ResponseService::error("We need the ID");
            exit; // stop further execution
        }
        $id=$_GET['id'];
        $this->autho($connection,$id);
        $AllTrainees=AdminService::GetAllTrainees($connection);
        if (empty($AllTrainees)) {
            echo ResponseService::error("No trainees found");
            return;
        }
        echo ResponseService::success($AllTrainees);


    }

    public function getTrainee()
    {   error_reporting(E_ERROR | E_PARSE);
        
        global $connection;
        if (empty($_GET['id'])) {
            echo ResponseService::error("We need the ID");
            exit; // stop further execution
        }
        $id=$_GET['id'];
        // Authorization check
        $this->autho($connection,$id);
    
        // Check if TraineeId is provided
        if (!isset($_GET["TraineeId"])) {
            echo ResponseService::error("TraineeId is required");
            exit;
        }
    
        $traineeId = $_GET["TraineeId"];
    
        // Get trainee data
        $specificTraineeData = AdminService::GetTraineeInfoDays($connection, $traineeId);
    
        if (empty($specificTraineeData)) {
            echo ResponseService::error("No info found");
            exit;
        }
    
        echo ResponseService::success($specificTraineeData);
    }

    public function deleteTraine() {
        global $connection;
        error_reporting(E_ERROR | E_PARSE);
        $input = json_decode(file_get_contents("php://input"), true);
        $_POST=$input;
        if (empty($_POST['id']) ||empty($_POST['dayId'])) {
            echo ResponseService::error("We need the ID");
            exit;
        }
    
        $id = $_POST['id'];
        $dayId=$_POST['dayId'];
        $this->autho($connection, $id);
    
        if (!isset($_POST["traineeid"])) {
            echo ResponseService::error("We need trainee ID");
            exit;
        }
    
        $traineeId = $_POST["traineeid"];
        $isdelete = AdminService::deleteTrainee($connection, $traineeId,$dayId);
    
        if ($isdelete) {
            echo ResponseService::success("true");
            exit;
        }
    
        echo ResponseService::error("Error deleting trainee");
    }
    
    public function deleteTraineeInfo() {
        error_reporting(E_ERROR | E_PARSE);
        global $connection;
        $input = json_decode(file_get_contents("php://input"), true);
        $_POST=$input;
    
        if (empty($_POST['id'])) {
            echo ResponseService::error("We need the ID");
            exit;
        }
    
        $id = $_POST['id'];
        $this->autho($connection, $id);
    
        if (!isset($_POST["traineeid"]) || !isset($_POST["day"])) {
            echo ResponseService::error("We need trainee ID and date");
            exit;
        }
    
        $isdelete = AdminService::deleteTraineeDayInfo($connection, $_POST["day"], $_POST["traineeid"]);
    
        if ($isdelete) {
            echo ResponseService::success("true");
            exit;
        }
    
        echo ResponseService::error("Error deleting trainee info");
    }

    public function updateTraineeDayInfo(){
        global $connection;
        if (empty($_POST['id'])) {
            echo ResponseService::error("We need the ID");
            exit;
        }
 
         $this->autho($connection,$id);
         if(!isset($_POST["traineeid"]) && !isset($_POST["day"])){
           echo ResponseService::error("We need trainee ID  and date");
         }

    }

          




        
    


    

}

?>