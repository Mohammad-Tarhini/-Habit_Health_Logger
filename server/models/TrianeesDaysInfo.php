<?php
require_once("Model.php");

class TraineesDaysInfo extends Model {
    private int $id;
    private int $user_id;
    private ?int $walk_minutes; 
    private ?int $walk_steps; 
    private ?float $sleep_hours;
    private ?string $wake_up_hour;
    private ?int $caffeine; 
    private ?int $calories_eaten;
    private ?int $calories_burned;
    private string $date;

    protected static string $table = "trainees_days_info";

    public function __construct(array $data) {
        $this->id = $data['id'] ?? 0;
        $this->user_id = $data['user_id'];
        $this->walk_minutes = $data['walk_minutes'] ?? null;
        $this->walk_steps = $data['walk_steps'] ?? null;
        $this->sleep_hours = $data['sleep_hours'] ?? null;
        $this->wake_up_hour = $data['wake_up_hour'] ?? null;
        $this->caffeine = $data['caffeine'] ?? null;
        $this->calories_eaten = $data['calories_eaten'] ?? null;
        $this->calories_burned = $data['calories_burned'] ?? null;
        $this->date = $data['date'];
    }

    // Getters
    public function getId() { return $this->id; }
    public function getUserId() { return $this->user_id; }
    public function getWalkMinutes() { return $this->walk_minutes; }
    public function getWalkSteps() { return $this->walk_steps; }
    public function getSleepHours() { return $this->sleep_hours; }
    public function getWakeUpHour() { return $this->wake_up_hour; }
    public function getCaffeine() { return $this->caffeine; }
    public function getCaloriesEaten() { return $this->calories_eaten; }
    public function getCaloriesBurned() { return $this->calories_burned; }
    public function getDate() { return $this->date; }

    // Setters
    public function setWalkMinutes(?int $walk_minutes) { $this->walk_minutes = $walk_minutes; }
    public function setWalkSteps(?int $walk_steps) { $this->walk_steps = $walk_steps; }
    public function setSleepHours(?float $sleep_hours) { $this->sleep_hours = $sleep_hours; }
    public function setWakeUpHour(?string $wake_up_hour) { $this->wake_up_hour = $wake_up_hour; }
    public function setCaffeine(?int $caffeine) { $this->caffeine = $caffeine; }
    public function setCaloriesEaten(?int $calories_eaten) { $this->calories_eaten = $calories_eaten; }
    public function setCaloriesBurned(?int $calories_burned) { $this->calories_burned = $calories_burned; }
    public function setDate(string $date) { $this->date = $date; }

    // Convert object to array (for DB insert/update)
    public function toArray() {
        return [
            "id" => $this->id,
            "user_id" => $this->user_id,
            "walk_minutes" => $this->walk_minutes,
            "walk_steps" => $this->walk_steps,
            "sleep_hours" => $this->sleep_hours,
            "wake_up_hour" => $this->wake_up_hour,
            "caffeine" => $this->caffeine,
            "calories_eaten" => $this->calories_eaten,
            "calories_burned" => $this->calories_burned,
            "date" => $this->date
        ];
    }
}
