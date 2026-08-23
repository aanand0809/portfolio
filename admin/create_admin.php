<?php

require_once "../php/db.php";

$name = "Anand Kumar";
$email = "admin@gmail.com";
$password = "Admin@12345";

// Secure password hash
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Check if admin already exists
$check = $conn->prepare("SELECT id FROM admins WHERE email = ? LIMIT 1");
$check->bind_param("s", $email);
$check->execute();

$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "Admin already exists.";
    exit;
}

$check->close();

// Insert admin
$stmt = $conn->prepare(
    "INSERT INTO admins (name, email, password) VALUES (?, ?, ?)"
);

$stmt->bind_param("sss", $name, $email, $passwordHash);

if ($stmt->execute()) {
    echo "Admin created successfully!<br><br>";
    echo "Email: " . htmlspecialchars($email) . "<br>";
    echo "Password: " . htmlspecialchars($password);
} else {
    echo "Error: " . htmlspecialchars($stmt->error);
}

$stmt->close();
$conn->close();

?>