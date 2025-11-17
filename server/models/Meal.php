<?php

class Meal extends Model
{
    private ?int $id;
    private string $meals;
    //private DateTime $datetime;
    private DateTime $day;
    private string $meal_categories;
    private int $user_id;


    protected static string $table="meals_log";
    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->meals = $data['meals'] ?? "";
        $this->datetime = $data['datetime'] ?? "";
        $this->meal_categories = $data['meal_categories'] ?? "";
        $this->user_id = $data['user_id'] ?? 0;
      //  $this->day = new DateTime($data['day']);
       // $this->datetime=new DateTime($date['datetime']);
        if (!empty($data['datetime'])) {
        $this->datetime = new DateTime($data['datetime']);
        } else {
            $this->datetime = new DateTime(); // default: now
        }

    }

    public function getId(): ?int { return $this->id; }
    public function getMeals(): string { return $this->meals; }
    public function getDateTime(): string { return $this->datetime->format("Y-m-d H:i:s"); }
    public function getMealCategories(): string { return $this->meal_categories; }
    public function getUserId(): int { return $this->user_id; }


    public function setMeals(string $meals) { $this->meals = $meals; }
    public function setMealCategories(string $mc) { $this->meal_categories = $mc; }
    public function setUserId(int $user_id) { $this->user_id = $user_id; }
    public function setDateTime(string|DateTime $datetime): void
    {
        // If string → convert to DateTime
        if (is_string($datetime)) {
            $this->datetime = new DateTime($datetime);
        } else {
            $this->datetime = $datetime;
        }
    }


    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "meals" => $this->meals,
            "datetime" => $this->datetime->format("Y-m-d H:i:s"),
            "meal_categories" => $this->meal_categories,
            "user_id" => $this->user_id
        ];
    }
}