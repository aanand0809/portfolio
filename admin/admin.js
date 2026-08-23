// ========================================
// ADMIN PANEL JAVASCRIPT
// ========================================


// ========================================
// PASSWORD SHOW / HIDE
// ========================================

const togglePassword = document.getElementById("togglePassword");
const passwordInput = document.getElementById("password");

if (togglePassword && passwordInput) {

    togglePassword.addEventListener("click", function () {

        if (passwordInput.type === "password") {

            passwordInput.type = "text";

            togglePassword.innerHTML =
                '<i class="fa-solid fa-eye-slash"></i>';

        } else {

            passwordInput.type = "password";

            togglePassword.innerHTML =
                '<i class="fa-solid fa-eye"></i>';
        }

    });
}


// ========================================
// ADMIN LOGIN FORM
// ========================================

const adminLoginForm =
    document.getElementById("adminLoginForm");

if (adminLoginForm) {

    adminLoginForm.addEventListener("submit", function (event) {

        event.preventDefault();

        const email =
            document.getElementById("email").value.trim();

        const password =
            document.getElementById("password").value.trim();

        const loginMessage =
            document.getElementById("loginMessage");

        // Basic validation
        if (email === "" || password === "") {

            loginMessage.style.display = "block";

            loginMessage.className =
                "login-message error";

            loginMessage.textContent =
                "Please enter email and password.";

            return;
        }

        // Temporary test
        loginMessage.style.display = "block";

        loginMessage.className =
            "login-message success";

        loginMessage.textContent =
            "Login system ready. PHP authentication will be connected next.";

        console.log("Admin Email:", email);

    });
}