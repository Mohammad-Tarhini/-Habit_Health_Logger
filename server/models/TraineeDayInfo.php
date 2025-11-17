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
        $this->id = $data['id'] ;
        $this->user_id = $data['user_id'];
        $this->exercise_minutes = $data['exercise_minutes'] ;
        $this->walk_minutes = $data['walk_minutes'] ;
        $this->steps = $data['steps'] ;
        $this->sleep_hour = $data['sleep_hour'] ;

        $this->caffeine = $data['caffeine'] ?? null;
        $this->calories_intake = $data['calories_intake'] ;
        $this->calories_burn = $data['calories_burn'] ;
       $this->day = new DateTime($data['day']);
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
