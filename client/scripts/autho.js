import { signin, signup } from "./component.js";
import { BASE_URL } from "./constants.js";

const container = document.getElementById("container");

const AuthAPI = {
    signup: (data) => axios.post(BASE_URL + "auth/signup", data,{
        headers: { "Content-Type": "application/x-www-form-urlencoded" }}),
    signin: (data) => axios.post(BASE_URL + "auth/signin", data,{
        headers: { "Content-Type": "application/x-www-form-urlencoded" }
    })
};

// ===================== SHOW SIGNIN =====================
export function showSignin() {
    container.innerHTML = signin();

    document.getElementById("signinBtn").onclick = async () => {
        const data = {
            email: document.getElementById("email").value,
            password: document.getElementById("password").value,
            role: document.getElementById("role").value,
        };

        try {
            const res = await AuthAPI.signin(data);

            

            if (res.data.success) {
                localStorage.setItem("userId", res.data.data.userId);
                localStorage.setItem("role", res.data.data.role);
                if(res.data.data.role==="admin"){
                    window.location.href="admin.html"
                }
                else if(res.data.data.role==="trainee"){
                    window.location.href="trainee.html"
                }
            }

        } catch (err) {
            const message =
                err?.response?.data?.message ||
                err?.response?.data ||
                err?.message ||
                "Unknown error";

            alert("Error: " + message);
        }
    };

    document.getElementById("toSignup").onclick = showSignup;
}
// ===================== SHOW SIGNUP =====================
export function showSignup() {
    container.innerHTML = signup();

    document.getElementById("signupBtn").onclick = async () => {
        const data = {
            name: document.getElementById("name").value,
            email: document.getElementById("email").value,
            password: document.getElementById("password").value,
            role: document.getElementById("role").value,
        };

        try {
            const res = await AuthAPI.signup(data);
           
             //alert("New userId: " + res.data.data.userId);

            if (res.data.success) {
                showSignin();
               
            }
        } catch (err) {
             const message =
                err?.response?.data?.message ||
                err?.response?.data ||
                err?.message ||
                "Unknown error";

            alert("Error: " + message);
        }
    };

    document.getElementById("toSignin").onclick = showSignin;
}

// Default page:
showSignin();
