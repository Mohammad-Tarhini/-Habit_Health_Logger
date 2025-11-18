// trainee.js
import { 
    dailyEntriesComponent, 
    addHabitComponent, 
    addMealComponent, 
    weeklySummaryComponent, 
    nutritionCardComponent,
    aiSuggestionComponent
} from "./component.js";
import { BASE_URL } from "./constants.js";

const container = document.getElementById("container");
const traineeId = localStorage.getItem("userId") || null;

const TraineeAPI = {
    sendText: (data) => axios.post(BASE_URL + "trainee/entriesAndHabits", data),

    addHabit: (data) => axios.post(BASE_URL + "trainee/AddHabitsManual", data),

    addMeal: (data) => axios.post(BASE_URL + "trainee/addMealsManual", data),

    weeklySummary: (params) => axios.post(BASE_URL + "trainee/weeklySummary", params),

    nutritionCard: (params) => axios.get(BASE_URL + "trainee/nutritionCoach", { params }),

    getAiSuggestion: (data) => axios.post(BASE_URL + "trainee/TakeSuggestionFromAi", data),
};

export function showTraineeDashboard() {
    
    container.innerHTML = `
        <div class="dashboard-grid">
            <aside class="sidebar" id="sidebar">
                <div class="sidebar-header">
                    <h3>Dashboard</h3>
                </div>
                <nav class="menu" id="menu">
                    <button class="menu-btn" data-section="dailySection">Daily Entries</button>
                    <button class="menu-btn" data-section="habitSection">Add Habit</button>
                    <button class="menu-btn" data-section="mealSection">Add Meal</button>
                    <button class="menu-btn" data-section="weeklySection">Weekly Summary</button>
                    <button class="menu-btn" data-section="nutritionSection">Nutrition Card</button>
                    <button class="menu-btn" data-section="aiSection">AI Suggestion</button>
                </nav>
            </aside>

            <main class="main-content" id="mainContent">
                ${dailyEntriesComponent()}
                ${addHabitComponent()}
                ${addMealComponent()}
                ${weeklySummaryComponent()}
                ${nutritionCardComponent()}
                ${aiSuggestionComponent()}
            </main>
        </div>
    `;

    attachEvents();

    showSection("dailySection");
}

function showSection(sectionId) {
    const sections = document.querySelectorAll(".dashboard-section");
    sections.forEach(sec => {
        sec.style.display = "none";
    });

    const active = document.getElementById(sectionId);
    if (active) active.style.display = "block";

    // update active menu button style
    document.querySelectorAll(".menu-btn").forEach(btn => {
        btn.classList.toggle("active", btn.getAttribute("data-section") === sectionId);
    });
}

function attachEvents() {
   
    document.querySelectorAll(".menu .menu-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            const sectionId = btn.getAttribute("data-section");
            showSection(sectionId);

        });
    });

    

    // Send AI Text
    const sendTextBtn = document.getElementById("sendTextBtn");
    if (sendTextBtn) {
        sendTextBtn.addEventListener("click", async () => {
                const payload = {
                    id: traineeId,
                    dayDate: document.getElementById("dayDate").value,
                    text: document.getElementById("text").value
                };

            aiResponse.innerText = "Saving...";
            try {
                const res =await TraineeAPI.sendText(payload);
                console.log(res);
                aiResponse.innerText = res.data.success ? "Entries saved!" : (res.data.message || "No response");
            } catch (err) {
                aiResponse.innerText = "Error: " + (err?.response?.data?.message || err.message || "Unknown");
            }
        });
    }

    // Add Habit
    const addHabitBtn = document.getElementById("addHabitBtn");
    if (addHabitBtn) {
        addHabitBtn.addEventListener("click", async () => {
            const data = {
                id: traineeId,
                exercise_minutes: document.getElementById("exercise_minutes").value,
                walk_minutes: document.getElementById("walk_minutes").value,
                steps: document.getElementById("steps").value,
                sleep_hour: document.getElementById("sleep_hour").value,
                caffeine: document.getElementById("caffeine").value,
                calories_intake: document.getElementById("calories_intake").value,
                calories_burn: document.getElementById("calories_burn").value,
                day: document.getElementById("habit_day").value
            };

            const habitResponse = document.getElementById("habitResponse");
            habitResponse.innerText = "Saving...";
            try {
                const res = await TraineeAPI.addHabit(data);
                console.log(res);
                habitResponse.innerText = res.data.success ? res.data.data : (res.data.message || "No response");
            } catch (err) {
                habitResponse.innerText = "Error: " + (err?.response?.data?.message || err.message || "Unknown");
            }
        });
    }

    // Add Meal
    const addMealBtn = document.getElementById("addMealBtn");
    if (addMealBtn) {
        addMealBtn.addEventListener("click", async () => {
            const data = {
                id: traineeId,
                meals: document.getElementById("meals").value,
                datetime: document.getElementById("meal_datetime").value,
                meal_categories: document.getElementById("meal_categories").value
            };

            const mealResponse = document.getElementById("mealResponse");
            mealResponse.innerText = "Saving...";
            try {
                const res = await TraineeAPI.addMeal(data);
                mealResponse.innerText = res.data.success ? res.data.data : (res.data.message || "No response");
            } catch (err) {
                mealResponse.innerText = "Error: " + (err?.response?.data?.message || err.message || "Unknown");
            }
        });
    }

    // Weekly Summary
    const weeklyBtn = document.getElementById("weeklySummaryBtn");
    if (weeklyBtn) {
        weeklyBtn.addEventListener("click", async () => {
            const data = {
                id: traineeId,
                dateTimeFrom: document.getElementById("dateTimeFrom").value,
                dateTimeTo: document.getElementById("dateTimeTo").value,
            };
            const weeklySummary = document.getElementById("weeklySummary");
            weeklySummary.innerText = "Loading...";
            try {
                const res = await TraineeAPI.weeklySummary(data);
                console.log(res);
                weeklySummary.innerText = res.data.success ? JSON.stringify(res.data.data, null, 2) : (res.data.message || "No response");
            } catch (err) {
                weeklySummary.innerText = "Error: " + (err?.response?.data?.message || err.message || "Unknown");
            }
        });
    }

    // Nutrition Coach
    const nutritionBtn = document.getElementById("nutritionBtn");
    if (nutritionBtn) {
        nutritionBtn.addEventListener("click", async () => {
            const nutritionCard = document.getElementById("nutritionCard");
            nutritionCard.innerText = "Loading...";
            try {
                const res = await TraineeAPI.nutritionCard({ id: traineeId });
               
                nutritionCard.innerText = res.data.success ? JSON.stringify(res.data.data, null, 2) : (res.data.message || "No response");
            } catch (err) {
                nutritionCard.innerText = "Error: " + (err?.response?.data?.message || err.message || "Unknown");
            }
        });
    }

    // AI Suggestion
    const getSuggestionBtn = document.getElementById("getSuggestionBtn");
    if (getSuggestionBtn) {
        getSuggestionBtn.addEventListener("click", async () => {
            const text = document.getElementById("suggestionText").value;
            const suggestionResponse = document.getElementById("suggestionResponse");

            if (!text) {
                suggestionResponse.innerText = "Please enter text first.";
                return;
            }

            suggestionResponse.innerText = "Loading...";
            try {
                const res = await TraineeAPI.getAiSuggestion({ id: traineeId, text });
                console.log(res)
                suggestionResponse.innerText = res.data.success ? res.data.data : (res.data.message || "No response");
            } catch (err) {
                suggestionResponse.innerText = "Error: " + (err?.response?.data?.message || err.message || "Unknown");
            }
        });
    }
}

// default render
showTraineeDashboard();
