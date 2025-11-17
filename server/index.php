<?php 
require_once( __dir__."/services/ResponseService.php");
require_once( __dir__."/routes/api.php");
 require_once (__dir__."/controllers/AdminController.php");
 require_once (__dir__."/controllers/AuthoController.php");
 require_once (__dir__."/controllers/TraineeController.php");
$base_dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (strpos($request, $base_dir) === 0) {
    $request = substr($request, strlen($base_dir));
}

if ($request == '') {
    $request = '/';
}
$apis = [
    // Admin endpoints
    '/admin/getAllTrainees'      => ['controller' => 'AdminController', 'method' => 'getAllTraineesForAdmin'],
    '/admin/getTrainee'          => ['controller' => 'AdminController', 'method' => 'getTrainee'],

    // Auth endpoints
    '/auth/signup'               => ['controller' => 'AuthoController', 'method' => 'signUp'],
    '/auth/signin'               => ['controller' => 'AuthoController', 'method' => 'signIn'],

    // Trainee endpoints
    '/trainee/entriesAndHabits'  => ['controller' => 'TraineeController', 'method' => 'EntriesAndHabitsByText'],
    '/trainee/weeklySummary'     => ['controller' => 'TraineeController', 'method' => 'weeklySummary'],
    '/trainee/nutritionCoach'    => ['controller' => 'TraineeController', 'method' => 'nutritionCoach'],
    '/trainee/AddHabitsManual'   =>['controller' =>'TraineeController','method'=>'addEntriesAndHabitsManual'],
    '/trainee/addMealsManual'   =>['controller'=>'TraineeController', 'method'=>'addMealsManual'],
    '/trainee/TakeSuggestionFromAi' =>['controller'=>'TraineeController' ,'method'=>'TakeSuggestionFromAi']
];

if (isset($apis[$request])) {
    $controller_name = $apis[$request]['controller']; 
    $method = $apis[$request]['method'];
    require_once "controllers/{$controller_name}.php";
    
    $controller = new $controller_name();
    if (method_exists($controller, $method)) {
        $controller->$method();
    } else {
        echo ResponseService::error( "Error: Method {$method} not found in {$controller_name}");
    }
} else {
    echo ResponseService::error("Route Not Found");
}

?>