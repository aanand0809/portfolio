<?php
session_start();

require_once "../php/db.php";

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit;
}

$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";

if ($email === "" || $password === "") {
    header("Location: login.php?error=1");
    exit;
}

// Find admin by email
$stmt = $conn->prepare(
    "SELECT id, name, email, password FROM admins WHERE email = ? LIMIT 1"
);

$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();
$admin = $result->fetch_assoc();

$stmt->close();

// Verify password
if ($admin && password_verify($password, $admin["password"])) {

    // Prevent session fixation
    session_regenerate_id(true);

    $_SESSION["admin_id"] = $admin["id"];
    $_SESSION["admin_name"] = $admin["name"];
    $_SESSION["admin_email"] = $admin["email"];

    header("Location: dashboard.php");
    exit;
}

// Login failed
header("Location: login.php?error=1");
exit;
?>