<?php
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
];


?>