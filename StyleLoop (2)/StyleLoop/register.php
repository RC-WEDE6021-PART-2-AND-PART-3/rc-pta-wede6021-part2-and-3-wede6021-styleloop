<?php
// register.php: allows a new customer to register. New users remain pending until verified by the administrator.
session_start();
include "DBConn.php";
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullName = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if ($fullName !== "" && $email !== "" && $username !== "" && $password !== "") {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO tblUser (FullName, Email, Username, PasswordHash, IsVerified) VALUES (?, ?, ?, ?, 0)");
        $stmt->bind_param("ssss", $fullName, $email, $username, $passwordHash);
        if ($stmt->execute()) {
            $message = "Registration successful. Your registration is pending until the administrator verifies you as a customer.";
        } else {
            $message = "Registration failed because the email or username already exists.";
        }
    } else {
        $message = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Register - StyleLoop</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include "header.php"; ?>
<div class="form-page">
    <div class="form-box">
        <div class="brand"><span class="brand-symbol">S</span>StyleLoop</div>
        <h2>Create Account</h2>
        <p class="success"><?php echo htmlspecialchars($message); ?></p>
        <form method="POST">
            <label>Full Name</label><input type="text" name="fullname" required>
            <label>Email Address</label><input type="email" name="email" required>
            <label>Username</label><input type="text" name="username" required>
            <label>Password</label><input type="password" name="password" required>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>
</body>
</html>
