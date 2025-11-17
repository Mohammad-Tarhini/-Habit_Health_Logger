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
        // Check if ID is provided
        if (empty($_GET['id'])) {
            echo ResponseService::error("We need the ID");
            exit; // stop further execution
        }
        $this->autho($connection,$id);
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
        if (empty($_GET['id'])) {
            echo ResponseService::error("We need the ID");
            exit; // stop further execution
        }
        // Authorization check
        $this->autho($connection,$id);
    
        // Check if TraineeId is provided
        if (!isset($_GET["TraineeId"])) {
            echo ResponseService::error("TraineeId is required");
            return;
        }
    
        $traineeId = $_GET["TraineeId"];
    
        // Get trainee data
        $specificTraineeData = AdminService::GetTraineeInfoDays($connection, $traineeId);
    
        if (empty($specificTraineeData)) {
            echo ResponseService::error("No info found");
            return;
        }
    
        echo ResponseService::success($specificTraineeData);
    }

    public function deleteTraine(){
        global $connection;
        if (empty($_POST['id'])) {
            echo ResponseService::error("We need the ID");
            exit;
        }
        
         $this->autho($connection,$id);
         if(!isset($_POST["traineeid"])){
           echo ResponseService::error("We need trainee ID");
         }
          $traineeid=$_POST["traineeid"];
         $isdelete= AdminService::deleteTrainee($connection,$traineeId);
         if($isdelete)
            echo ResponseService::success("true");
            exit;
        echo  ResponseService::error("errorrj");
    

    }

    public function deleteTraineeInfo(){
        global $connection;
        if (empty($_POST['id'])) {
            echo ResponseService::error("We need the ID");
            exit;
        }
        
         $this->autho($connection,$id);
         if(!isset($_POST["traineeid"]) && !isset($_POST["day"])){
           echo ResponseService::error("We need trainee ID  and date");
           exit;
         }
         $isdelete=AdminService::deleteTraineeDayInfo($connection,$_POST["day"],$_POST["traineeid"]);
         if($isdelete){
             echo ResponseService::success("true");
            exit;
         }
         echo ResponseService::error("error in delete ");

    }
    // public function updateTraineeDayInfo(){
    //     global $connection;
    //     if (empty($_POST['id'])) {
    //         echo ResponseService::error("We need the ID");
    //         exit;
    //     }
        
    //      $this->autho($connection,$id);
    //      if(!isset($_POST["traineeid"]) && !isset($_POST["day"])){
    //        echo ResponseService::error("We need trainee ID  and date");
    //      }

    // }

          




        
    


    

}

?>