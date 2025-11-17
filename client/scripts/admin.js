import { BASE_URL } from "./constants.js";
import { checkAuth } from "./authGuard.js";
const traineesTable = document.getElementById("traineesTable");
const traineeDetailsTable = document.getElementById("traineeDetailsTable");

const AdminAPI = {
getAllTrainees: (id) => axios.get(`${BASE_URL}admin/getAllTrainees?id=${id}`),
getTrainee: (id, traineeId) => axios.get(BASE_URL + 'admin/getTrainee', { params: { id, TraineeId: traineeId } }),
};
const admainid=localStorage.getItem("userId")
const role=localStorage.getItem("role")
checkAuth(role);

// ======================= RENDER ALL TRAINEES ===========================
export async function loadAllTrainees(admainid) {
     
    try {
        const res = await AdminAPI.getAllTrainees(admainid);
        
        const trainees = res.data.data;
      console.log(trainees)
        traineesTable.innerHTML = `
            <h2>All Trainees</h2>
            <table border="1" cellpadding="8">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    ${trainees.map(t => `
                        <tr class="traineeRow" data-id="${t.id}">
                            <td>${t.id}</td>
                            <td>${t.name}</td>
                            <td>${t.email}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;

        // attach click events
        document.querySelectorAll(".traineeRow").forEach(row => {
            row.onclick = () => loadTraineeDetails(admainid,row.dataset.id);
        });

    } catch (error) {
        alert("Error loading trainees.");
    }
}

// ======================= RENDER TRAINEE DETAILS ===========================
async function loadTraineeDetails(admainid,traineeid) {
    try {
        const res = await AdminAPI.getTrainee(admainid,traineeid);
        console.log(res.data)
         if(!res.success){
             traineeDetailsTable.innerHTML="no data"
         }
        const days = res.data.data;
        console.log(days)

        traineeDetailsTable.innerHTML = `
            <h2>Trainee Progress (ID: ${traineeid})</h2>
            <table border="1" cellpadding="8">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Walk Minutes</th>
                        <th>Walk Steps</th>
                        <th>Sleep Hours</th>
                        <th>Wake Up Hour</th>
                        <th>Caffeine</th>
                        <th>Calories Eaten</th>
                        <th>Calories Burned</th>
                    </tr>
                </thead>
                <tbody>
                    ${days.map(d => `
                        <tr>
                            <td>${d.id}</td>
                            <td>${d.day_date}</td>
                            <td>${d.walk_minutes}</td>
                            <td>${d.walk_steps}</td>
                            <td>${d.sleep_hours}</td>
                            <td>${d.wake_up_hour}</td>
                            <td>${d.caffeine}</td>
                            <td>${d.calories_eaten}</td>
                            <td>${d.calories_burned}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;

    } catch (error) {
        alert("Error loading trainee details.");
    }

}
loadAllTrainees(admainid)
