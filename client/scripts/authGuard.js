// authGuard.js

export function checkAuth(role) {
    const id = localStorage.getItem("id");
    const storagerole=localStorage.getItem("role");

    if (role !=storagerole) {
        window.location.href = "auth.html";  
    }
}