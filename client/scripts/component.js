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
// component.js
export function dailyEntriesComponent() {
    return `
    <section id="dailySection" class="dashboard-section">
        <h2>Enter Daily Habits & Entries By text</h2>
        <input type="date" id="dayDate">
        <textarea id="text" placeholder="Enter description for AI..."></textarea>
        <button id="sendTextBtn">Save data of text</button>
        <div id="aiResponse" class="response"></div>
        <div id="askingAi" class="response"></div>
    </section>
    `;
}

export function addHabitComponent() {
    return `
    <section id="habitSection" class="dashboard-section">
        <h2>Add Daily Habits Manually</h2>
        <input type="number" id="exercise_minutes" placeholder="Exercise Minutes">
        <input type="number" id="walk_minutes" placeholder="Walk Minutes">
        <input type="number" id="steps" placeholder="Steps">
        <input type="number" id="sleep_hour" placeholder="Sleep Hours">
        <input type="number" id="caffeine" placeholder="Caffeine (mg)">
        <input type="number" id="calories_intake" placeholder="Calories Intake">
        <input type="number" id="calories_burn" placeholder="Calories Burned">
        <input type="date" id="habit_day">
        <button id="addHabitBtn">Add Habit</button>
        <div id="habitResponse" class="response"></div>
    </section>
    `;
}

export function addMealComponent() {
    return `
    <section id="mealSection" class="dashboard-section">
        <h2>Add Meals Manually</h2>
        <input type="text" id="meals" placeholder="Meal Description">
        <input type="datetime-local" id="meal_datetime">
        <input type="text" id="meal_categories" placeholder="Meal Category">
        <button id="addMealBtn">Add Meal</button>
        <div id="mealResponse" class="response"></div>
    </section>
    `;
}

export function weeklySummaryComponent() {
    return `
    <section id="weeklySection" class="dashboard-section">
        <h2>Weekly Summary</h2>
        <input type="datetime-local" id="dateTimeFrom" >
        <input type="datetime-local" id="dateTimeTo">
        <button id="weeklySummaryBtn">Get Weekly Summary</button>
        <div id="weeklySummary" class="response"></div>
    </section>
    `;
}

export function nutritionCardComponent() {
    return `
    <section id="nutritionSection" class="dashboard-section">
        <h2>Nutrition Coach Card</h2>
        <button id="nutritionBtn">Get Nutrition Advice</button>
        <div id="nutritionCard" class="response"></div>
    </section>
    `;
}

export function aiSuggestionComponent() {
    return `
    <section id="aiSection" class="dashboard-section">
        <h2>Get AI Suggestion</h2>
        <textarea id="suggestionText" placeholder="Enter your text for AI suggestion..."></textarea>
        <button id="getSuggestionBtn">Get Suggestion</button>
        <div id="suggestionResponse" class="response"></div>
    </section>
    `;
}
