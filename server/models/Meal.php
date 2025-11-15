<?php

class Meal extends Model
{
    private ?int $id;
    private string $meals;
    private string $datetime;
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
    }

    public function getId(): ?int { return $this->id; }
    public function getMeals(): string { return $this->meals; }
    public function getDateTime(): string { return $this->datetime; }
    public function getMealCategories(): string { return $this->meal_categories; }
    public function getUserId(): int { return $this->user_id; }


    public function setMeals(string $meals) { $this->meals = $meals; }
    public function setDateTime(string $datetime) { $this->datetime = $datetime; }
    public function setMealCategories(string $mc) { $this->meal_categories = $mc; }
    public function setUserId(int $user_id) { $this->user_id = $user_id; }


    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "meals" => $this->meals,
            "datetime" => $this->datetime,
            "meal_categories" => $this->meal_categories,
            "user_id" => $this->user_id
        ];
    }
}