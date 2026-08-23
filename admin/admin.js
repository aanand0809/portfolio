/**
 * ADMIN PANEL JAVASCRIPT
 * Password visibility toggle and dynamic login submission handler
 */

document.addEventListener("DOMContentLoaded", () => {
    // Password visibility toggle
    const togglePasswordBtn = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");
    const toggleIcon = document.getElementById("toggleIcon");

    if (togglePasswordBtn && passwordInput && toggleIcon) {
        togglePasswordBtn.addEventListener("click", () => {
            const isPassword = passwordInput.type === "password";
            passwordInput.type = isPassword ? "text" : "password";
            toggleIcon.classList.toggle("fa-eye", !isPassword);
            toggleIcon.classList.toggle("fa-eye-slash", isPassword);
        });
    }

    // Admin login form handler
    const adminLoginForm = document.getElementById("adminLoginForm");
    const loginMessage = document.getElementById("loginMessage");

    if (adminLoginForm) {
        adminLoginForm.addEventListener("submit", async (e) => {
            const email = document.getElementById("email").value.trim();
            const password = document.getElementById("password").value.trim();

            if (!email || !password) {
                e.preventDefault();
                if (loginMessage) {
                    loginMessage.style.display = "block";
                    loginMessage.className = "login-message error";
                    loginMessage.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> Please enter both email and password.';
                }
                return;
            }

            // If running on a static hosting environment where PHP is not executed, show helpful info
            try {
                const testResp = await fetch("auth.php", { method: "HEAD" });
                if (!testResp.ok && testResp.status === 405) {
                    e.preventDefault();
                    if (loginMessage) {
                        loginMessage.style.display = "block";
                        loginMessage.className = "login-message error";
                        loginMessage.innerHTML = '<i class="fa-solid fa-circle-info"></i> The PHP Admin Backend requires a running MySQL/PHP server (e.g. XAMPP). All contact messages are also delivered directly to your Gmail!';
                    }
                }
            } catch (err) {
                // Let standard form POST proceed to auth.php on local server
            }
        });
    }
});