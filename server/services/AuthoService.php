<?php
require_once __DIR__ . '/../controllers/AuthoController.php';
require_once __DIR__ . '/../models/User.php';



class AuthoService {

    public static function validateUserData(User $user) {
        $name = trim($user->getName());
        $email = trim($user->getEmail());
        $role = trim($user->getRole());
        $password = $user->getPassword();

        if ($name === "") return "Name is required";
        if (strpos($email, '@') === false || strpos($email, '.') === false) return "Invalid email format";
        if (!in_array($role, ["trainee", "admin"])) return "Role must be 'trainee' or 'admin'";
        if (strlen($password) < 6) return "Password must be at least 6 characters long";

        return true;
    }

    public static function checkUserExist(string $email, mysqli $connection) {
        $users = User::findByColumn($connection, "email", $email);
         if ($users === null) {
        $users = [];
        }
        return (count($users) > 0) ? $users : "";
    }

    public static function signUp(User $user, mysqli $connection) {
        //  Validate data
        $validateResult = self::validateUserData($user);
        if ($validateResult !== true) return $validateResult;

        //  Check if user exists
        if (self::checkUserExist($user->getEmail(), $connection) !== "") {
            return "User already exists";
        }

        // Hash password
        $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);
        $user->setPassword($hashedPassword);

        // Insert user
        $userId = $user->insert($connection);
        if (!$userId) return "Error while inserting user";

        return $userId; // return new user ID
    }



    
    
    public static function signIn(string $email, string $password, string $role, mysqli $connection)
    {
        if (strpos($email, '@') === false || strpos($email, '.') === false) {
            return "Invalid email format";
        }

        if (strlen($password) < 6) {
            return "Password must be at least 6 characters long";
        }

        $usersExist = self::checkUserExist($email, $connection);
        if ($usersExist === "") {
            return "User does not exist";
        }
        $users = User::findByColumn($connection, "email", $email) ?? [];
        $user=$users[0];
        if($user===[])
            return "is not found ";

        $userRole=$user->getrole();
        if($userRole !==$role){
            return "is not correct  role ";
        }
         
        
        if (!password_verify($password, $user->getPassword())) {
            return "Invalid password";
        }
         $userId= $user->getID();
        return $userId ; 
    }

   
}
?>
