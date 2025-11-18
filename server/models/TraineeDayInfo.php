<?php
require_once("Model.php");

class TraineeDayInfo extends Model {
    private ?int $id;
    private int $user_id;
    private ?int $exercise_minutes;
    private ?int $walk_minutes;
    private ?int $steps;
    private ?float $sleep_hour;
    private ?int $caffeine;
    private ?int $calories_intake;
    private ?int $calories_burn;
    private DateTime $day;

    protected static string $table = "trainees_days_info";

   
    public function __construct(array $data) {
        $this->id = $data['id'] ?? null;
        $this->user_id = isset($data['user_id']) ? (int)$data['user_id'] : 0;
    
        $this->exercise_minutes = isset($data['exercise_minutes']) ? (int)$data['exercise_minutes'] : null;
        $this->walk_minutes     = isset($data['walk_minutes']) ? (int)$data['walk_minutes'] : null;
        $this->steps            = isset($data['steps']) ? (int)$data['steps'] : null;
        $this->sleep_hour       = isset($data['sleep_hour']) ? (float)$data['sleep_hour'] : null;
    
        $this->caffeine         = isset($data['caffeine']) ? (int)$data['caffeine'] : null;
        $this->calories_intake  = isset($data['calories_intake']) ? (int)$data['calories_intake'] : null;
        $this->calories_burn    = isset($data['calories_burn']) ? (int)$data['calories_burn'] : null;
    
        $this->day = isset($data['day']) ? new DateTime($data['day']) : new DateTime();
    }

    

    // Getters
    public function getId() { return $this->id; }
    public function getUserId() { return $this->user_id; }
    public function getExerciseMinutes() { return $this->exercise_minutes; }
    public function getWalkMinutes() { return $this->walk_minutes; }
    public function getSteps() { return $this->steps; }
    public function getSleepHour() { return $this->sleep_hour; }
    public function getCaffeine() { return $this->caffeine; }
    public function getCaloriesIntake() { return $this->calories_intake; }
    public function getCaloriesBurn() { return $this->calories_burn; }
    public function getDay() { return $this->day; }

    // Setters
    public function setExerciseMinutes(?int $exercise_minutes) { $this->exercise_minutes = $exercise_minutes; }
    public function setWalkMinutes(?int $walk_minutes) { $this->walk_minutes = $walk_minutes; }
    public function setSteps(?int $steps) { $this->steps = $steps; }
    public function setSleepHour(?float $sleep_hour) { $this->sleep_hour = $sleep_hour; }
    public function setCaffeine(?int $caffeine) { $this->caffeine = $caffeine; }
    public function setCaloriesIntake(?int $calories_intake) { $this->calories_intake = $calories_intake; }
    public function setCaloriesBurn(?int $calories_burn) { $this->calories_burn = $calories_burn; }
    public function setDay(string $day) { $this->day = $day; }

    // Convert object to array for DB
    public function toArray() {
        return [
            "id" => $this->id,
            "user_id" => $this->user_id,
            "exercise_minutes" => $this->exercise_minutes,
            "walk_minutes" => $this->walk_minutes,
            "steps" => $this->steps,
            "sleep_hour" => $this->sleep_hour,
            "caffeine" => $this->caffeine,
            "calories_intake" => $this->calories_intake,
            "calories_burn" => $this->calories_burn,
            "day" => $this->day->format("Y-m-d")
        ];
    }
}
?>
