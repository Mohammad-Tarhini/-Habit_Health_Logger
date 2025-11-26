Habit & Health Logger — PHP Backend + Vanilla JS Frontend

This project is a Habit & Health Tracking Platform that allows trainees to log daily habits, meals, exercise, and receive AI suggestions — while admins manage trainees and monitor their progress.

The application includes:

PHP backend (custom MVC-like structure)

MySQL database

Vanilla JavaScript frontend

AI-powered suggestions system

Admin dashboard + Trainee dashboard

🚀 Features
👨‍🎓 Trainee Features

Add daily entries (exercise, walk minutes, sleep hours, steps, calories, caffeine).

Add meals with categories and timestamps.

Submit free-text logs → backend converts them into structured data.

View weekly summary.

Get AI-generated habit improvement suggestions.

Access nutrition coach information.

🛠️ Admin Features

List all trainees.

View detailed daily logs for each trainee.

Delete trainee entirely (meals + habit logs + user account).

Delete specific day records.

Delete specific meal/entry.

Validate trainee identity and role using middleware-based authorization.

🔧 Backend Architecture
/controllers
    - AdminController.php
    - AuthoController.php
    - TraineeController.php

/services
    - AdminService.php
    - AuthoService.php
    - TraineeService.php
    - ResponseService.php

/models
    - User.php
    - TraineeDayInfo.php
    - Meal.php
    - Model.php  (base model)

middleware.php
connection/connection.php
index.php (routes)


The backend follows a simple clean-service architecture:

Controllers → Validate input + Handle HTTP requests

Services → Application logic

Models → Handle DB operations

Middleware → Authorization

ResponseService → Unified JSON format

🧠 Authorization Logic (Middleware)

Each endpoint validates user identity and role:

$role = Middleware::Authorization($connection, $userId);

if ($role !== "admin") {
    ResponseService::error("User is not an admin");
}


This ensures secure access for both trainees and admins.

🧮 Key Functionalities (Technical Overview)
🔹 Authentication

Signup with validation (email, password, role)

Password hashing (PHP password_hash)

Login with password verification

Prevent duplicate accounts

🔹 Trainee Operations

Insert structured day info

Insert meals

AI feedback from text

Weekly summaries

Manual data insertion for all habit fields

🔹 Admin Operations

Get all trainees

Get a trainee and all daily logs

Delete trainee (cascade delete)

Delete single day entry

All responses are unified using:

ResponseService::success(...)
ResponseService::error(...)

🖥️ Frontend (Vanilla JavaScript)

The project includes a minimal lightweight frontend:

HTML + CSS + Native JavaScript

Fetch API for sending requests

No frameworks → fast, simple, easy to deploy

Fully compatible with REST endpoints

Example call:

fetch("server/trainee/EntriesAndHabitsByText", {
    method: "POST",
    body: JSON.stringify({
        id: traineeId,
        dayDate: "2025-01-05",
        text: "Walked 40 minutes and slept 7 hours"
    })
})
.then(res => res.json())
.then(console.log);

🗄️ Database Structure (Summary)
users

| id | name | email | password | role |

trainees_days_info

| id | user_id | exercise_minutes | walk_minutes | steps | sleep_hour | caffeine | calories_intake | calories_burn | day |

meals_log

| id | user_id | meals | meal_categories | datetime |

▶️ How to Run the Project
1. Clone Repository
git clone https://github.com/YOUR_USERNAME/Habit_Health_Logger.git
cd Habit_Health_Logger

2. Configure Database

Update database credentials in:

/connection/connection.php

3. Start Backend

Use XAMPP / WAMP / Laragon and place files in:

htdocs/Habit_Health_Logger/

4. Start Frontend

Open the frontend folder:

frontend/index.html


Built using plain HTML, CSS, and JavaScript.

📌 Technologies Used

PHP 8

MySQL

Native MVC structure (custom)

Vanilla JavaScript

REST API

AI processing module

JSON-based communication

👨‍💻 About the Developer

Mohammad Baker Tarhini
Full Stack Developer | Backend Specialist
Passionate about AI, scalable systems, and clean architecture.
