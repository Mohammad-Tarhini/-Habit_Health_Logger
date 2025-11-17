import { 
    dailyEntriesComponent, 
    addHabitComponent, 
    addMealComponent, 
    weeklySummaryComponent, 
    nutritionCardComponent 
} from "./component.js";
import { BASE_URL } from "./constants.js";

const container = document.getElementById("container");
const traineeId = localStorage.getItem("userId");

const TraineeAPI = {
    sendText: (params) => axios.get(BASE_URL + "trainee/entriesAndHabits", { params }),
    addHabit: (data) => axios.post(BASE_URL + "trainee/AddHabitsManual", data),
    addMeal: (data) => axios.post(BASE_URL + "trainee/addMealsManual", data),
    weeklySummary: (params) => axios.get(BASE_URL + "trainee/weeklySummary", { params }),
    nutritionCard: (params) => axios.get(BASE_URL + "trainee/nutritionCoach", { params })
};

// ---------------- RENDER ALL COMPONENTS ----------------
export function showTraineeDashboard() {
    container.innerHTML = `
        ${dailyEntriesComponent()}
        ${addHabitComponent()}
        ${addMealComponent()}
        ${weeklySummaryComponent()}
        ${nutritionCardComponent()}
    `;

    attachEvents();
}

// ---------------- ATTACH EVENTS ----------------
function attachEvents() {
    // Send AI Text
    document.getElementById("sendTextBtn").onclick = async () => {
        const dayDate = document.getElementById("dayDate").value;
        const text = document.getElementById("text").value;

        try {
            const res = await TraineeAPI.sendText({ id: traineeId, dayDate, text });
            document.getElementById("aiResponse").innerText = res.data.success ? "Entries saved!" : res.data.message;
            console.log(res);
        } catch (err) {
            document.getElementById("aiResponse").innerText = "Error: " + err.response?.data?.message;
        }
    };

    // Add Habit
    document.getElementById("addHabitBtn").onclick = async () => {
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

        try {
            const res = await TraineeAPI.addHabit(data);
            document.getElementById("habitResponse").innerText = res.data.success ? res.data.data : res.data.message;
        } catch (err) {
            document.getElementById("habitResponse").innerText = "Error: " + err.response?.data?.message;
        }
    };

    // Add Meal
    document.getElementById("addMealBtn").onclick = async () => {
        const data = {
            id: traineeId,
            meals: document.getElementById("meals").value,
            datetime: document.getElementById("meal_datetime").value,
            meal_categories: document.getElementById("meal_categories").value
        };

        try {
            const res = await TraineeAPI.addMeal(data);
            document.getElementById("mealResponse").innerText = res.data.success ? res.data.data : res.data.message;
        } catch (err) {
            document.getElementById("mealResponse").innerText = "Error: " + err.response?.data?.message;
        }
    };

    // Weekly Summary
    document.getElementById("weeklySummaryBtn").onclick = async () => {
        try {
            const res = await TraineeAPI.weeklySummary({ id: traineeId });
            document.getElementById("weeklySummary").innerText = res.data.success ? JSON.stringify(res.data.data, null, 2) : res.data.message;
        } catch (err) {
            document.getElementById("weeklySummary").innerText = "Error: " + err.response?.data?.message;
        }
    };

    // Nutrition Coach
    document.getElementById("nutritionBtn").onclick = async () => {
        try {
            const res = await TraineeAPI.nutritionCard({ id: traineeId });
            document.getElementById("nutritionCard").innerText = res.data.success ? JSON.stringify(res.data.data, null, 2) : res.data.message;
        } catch (err) {
            document.getElementById("nutritionCard").innerText = "Error: " + err.response?.data?.message;
        }
    };
}

// ---------------- DEFAULT PAGE ----------------
showTraineeDashboard();
