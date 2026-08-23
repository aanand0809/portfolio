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

    <link rel="stylesheet" href="admin.css">
</head>

<body>

<div class="login-container">

    <div class="login-card">

        <div class="login-icon">
            🔐
        </div>

        <h1>Admin Login</h1>

        <p class="login-subtitle">
            Welcome back! Please login to continue.
        </p>

        <?php
        if (isset($_GET['error'])) {
            echo '<div class="error-message">
                    ❌ Invalid email or password.
                  </div>';
        }
        ?>

        <form action="auth.php" method="POST">

            <div class="form-group">
                <label for="email">Email</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter admin email"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter password"
                    required
                >
            </div>

            <button type="submit" class="login-btn">
                Login →
            </button>

        </form>

        <a href="../index.html" class="back-link">
            ← Back to Portfolio
        </a>

    </div>

</div>

</body>
</html>