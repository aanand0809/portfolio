<?php
session_start();

if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Anand Kumar</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Admin CSS -->
    <link rel="stylesheet" href="admin.css">
</head>

<body>

<div class="login-container">
    <div class="login-card">
        <div class="admin-logo">
            <i class="fa-solid fa-user-shield"></i>
        </div>

        <h1>Admin Login</h1>
        <p class="subtitle">Welcome back! Please login to manage your portfolio.</p>

        <?php if (isset($_GET['error'])): ?>
            <div class="error-message">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>Invalid email or password. Please try again.</span>
            </div>
        <?php endif; ?>

        <form action="auth.php" method="POST">
            <div class="input-group">
                <label for="email">
                    <i class="fa-solid fa-envelope"></i> Email Address
                </label>
                <input type="email" id="email" name="email" placeholder="admin@gmail.com" required autocomplete="email">
            </div>

            <div class="input-group">
                <label for="password">
                    <i class="fa-solid fa-lock"></i> Password
                </label>
                <div class="password-box">
                    <input type="password" id="password" name="password" placeholder="Enter password" required autocomplete="current-password">
                    <button type="button" id="togglePassword" class="toggle-password" title="Toggle password visibility">
                        <i class="fa-solid fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="login-btn">
                <span>Login</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>

        <a href="../index.html" class="back-link">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Portfolio</span>
        </a>
    </div>
</div>

<script>
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
</script>

</body>
</html>