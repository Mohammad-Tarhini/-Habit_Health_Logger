export function signin() {
    return `
        <h1>Sign In</h1>

        <input id="email" type="email" placeholder="Email"><br><br>
        <input id="password" type="password" placeholder="Password"><br><br>
        <input id="role" type="text" placeholder="Role"><br><br>

        <button id="signinBtn">Sign In</button>

        <p>Don't have an account? 
            <button id="toSignup">Create one</button>
        </p>
    `;
}

export function signup() {
    return `
        <h1>Create Account</h1>

        <input id="name" type="text" placeholder="Full Name"><br><br>
        <input id="email" type="email" placeholder="Email"><br><br>
        <input id="password" type="password" placeholder="Password"><br><br>
        <input id="role" type="text" placeholder="Role"><br><br>

        <button id="signupBtn">Sign Up</button>

        <p>Already have an account?
            <button id="toSignin">Sign In</button>
        </p>
    `;
}

// components.js
export function dailyEntriesComponent() {
    return `
    <section>
        <h2>View Daily Habits & Entries</h2>
        <input type="date" id="dayDate">
        <textarea id="text" placeholder="Enter description for AI..."></textarea>
        <button id="sendTextBtn">save data of text</button>
        <div id="aiResponse"></div>
    </section>
    `;
}

export function addHabitComponent() {
    return `
    <section>
        <h2>Add Habits Manually</h2>
        <input type="number" id="exercise_minutes" placeholder="Exercise Minutes">
        <input type="number" id="walk_minutes" placeholder="Walk Minutes">
        <input type="number" id="steps" placeholder="Steps">
        <input type="number" id="sleep_hour" placeholder="Sleep Hours">
        <input type="number" id="caffeine" placeholder="Caffeine (mg)">
        <input type="number" id="calories_intake" placeholder="Calories Intake">
        <input type="number" id="calories_burn" placeholder="Calories Burned">
        <input type="date" id="habit_day">
        <button id="addHabitBtn">Add Habit</button>
        <div id="habitResponse"></div>
    </section>
    `;
}

export function addMealComponent() {
    return `
    <section>
        <h2>Add Meals Manually</h2>
        <input type="text" id="meals" placeholder="Meal Description">
        <input type="datetime-local" id="meal_datetime">
        <input type="text" id="meal_categories" placeholder="Meal Category">
        <button id="addMealBtn">Add Meal</button>
        <div id="mealResponse"></div>
    </section>
    `;
}

export function weeklySummaryComponent() {
    return `
    <section>
        <h2>Weekly Summary</h2>
        <button id="weeklySummaryBtn">Get Weekly Summary</button>
        <div id="weeklySummary"></div>
    </section>
    `;
}

export function nutritionCardComponent() {
    return `
    <section>
        <h2>Nutrition Coach Card</h2>
        <button id="nutritionBtn">Get Nutrition Advice</button>
        <div id="nutritionCard"></div>
    </section>
    `;
}


