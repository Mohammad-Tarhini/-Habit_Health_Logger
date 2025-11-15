<?php
require_once(__DIR__ .'/../models/User.php');
require_once(__DIR__ .'/../connection/connection.php');
require_once(__DIR__ .'/../services/ResponseService.php');
require_once(__DIR__.'/../services/AdminService.php');
require_once(__DIR__.'/../middleware.php');

class AdminController{

    public static function autho($connection)
    {
        // Check if ID is provided
        if (empty($_GET['id'])) {
            echo ResponseService::error("We need the ID");
            exit; // stop further execution
        }
    
        // Authorization
        $Autho = Middleware::Authorization($connection,$_GET['id']);
    
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
        global $connection;
        $this->autho($connection);
        $AllTrainees=AdminService::GetAllTrainees($connection);
        if (empty($AllTrainees)) {
            echo ResponseService::error("No trainees found");
            return;
        }
        echo ResponseService::success($AllTrainees);


    }

    public function getTrainee()
    {
        global $connection;
    
        // Authorization check
        $this->autho($connection);
    
        // Check if TraineeId is provided
        if (!isset($_GET["TraineeId"])) {
            echo ResponseService::error("TraineeId is required");
            return;
        }
    
        $traineeId = $_GET["TraineeId"];
    
        // Get trainee data
        $specificTraineeData = AdminService::GetTraineeInfoDays($connection, $traineeId);
    
        if (empty($specificTraineeData)) {
            echo ResponseService::error("No trainee found");
            return;
        }
    
        echo ResponseService::success($specificTraineeData);
    }



    

}

?>