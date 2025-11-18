import { BASE_URL } from "./constants.js";
import { checkAuth } from "./authGuard.js";
const traineesTable = document.getElementById("traineesTable");
const traineeDetailsTable = document.getElementById("traineeDetailsTable");

const AdminAPI = {
getAllTrainees: (id) => axios.get(`${BASE_URL}admin/getAllTrainees?id=${id}`),
getTrainee: (id, traineeId) => axios.get(BASE_URL + 'admin/getTrainee', { params: { id, TraineeId: traineeId },
 }),
 deleteTrainee: (id, traineeId) => 
    axios.post(`${BASE_URL}admin/deleteTraine`, { id, traineeid: traineeId }),

deleteTraineeDayInfo: (id, traineeId, rowId) =>
    axios.post(`${BASE_URL}admin/deleteTraineeInfo`, { id, traineeid: traineeId, rowId }),
};
const admainid=localStorage.getItem("userId")
const role=localStorage.getItem("role")
checkAuth(role);

// ALL TRAINEES 
export async function loadAllTrainees(admainid) {
     
    try {
        const res = await AdminAPI.getAllTrainees(admainid);
        console.log(res);
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
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    ${trainees.map(t => `
                        <tr class="traineeRow" data-id="${t.id}">
                            <td>${t.id}</td>
                            <td>${t.name}</td>
                            <td>${t.email}</td>
                            <td>
                                <button class="deleteTraineeBtn" data-trainee="${t.id}">
                                    Delete
                                </button>
                            </td>                    
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;

        // attach click events
        document.querySelectorAll(".traineeRow").forEach(row => {
            row.addEventListener("click",() => loadTraineeDetails(admainid,row.dataset.id));
        });

        document.querySelectorAll(".deleteTraineeBtn").forEach(btn => {
         btn.addEventListener( "click", async (event) => {
          event.stopPropagation();
        if (!confirm("Are you sure you want to delete this trainee?")) return;

        const traineeId = btn.dataset.trainee;

        const res = await AdminAPI.deleteTrainee(admainid, traineeId);
        

        if (res.data.success) {
            alert("Trainee deleted!");
            loadAllTrainees(admainid); // refresh table
        } else {
            alert("Error deleting trainee");
        }
    });
});

    } catch (error) {
        alert("Error loading trainees.");
    }
}


// RENDER TRAINEE DETAILS 
async function loadTraineeDetails(admainid,traineeid) {
    try {
        const res = await AdminAPI.getTrainee(admainid,traineeid);
        console.log(res);
         if(!res.data.success){
            
             alert("no data for this user");
             return
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
                        <th>delete</th>
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
                            <td>
                                <button class="deleteDayBtn"
                                    data-trainee="${traineeid}"
                                    data-id="${d.id}">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
        document.querySelectorAll(".deleteDayBtn").forEach(btn => {
        btn.onclick = async () => {
            if (!confirm("Delete this day info?")) return;
    
            const traineeId = btn.dataset.trainee;
            const id = btn.dataset.id;
    
            const res = await AdminAPI.deleteTraineeDayInfo(admainid, traineeId, id);
    
            if (res.data.success) {
                alert("Deleted day info!");
                console.log(res);
               // loadTraineeDetails(admainid, traineeId); // refresh details
            } else {
                alert("Error deleting day info");
            }
        };
});


    } catch (error) {
        alert("Error loading trainee details.");
    }

}


loadAllTrainees(admainid)
