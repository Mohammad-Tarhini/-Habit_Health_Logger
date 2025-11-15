<?php
require_once __DIR__ . "/models/User.php";

class Middleware
{
    public static function Authorization(mysqli $connection, int $id)
    {
        if ($id === 0) return false;
    
        $user = User::findById($connection, $id);
    
       if (empty($user))return false;
    
       return $user->getrole(); 
    }
}

?>