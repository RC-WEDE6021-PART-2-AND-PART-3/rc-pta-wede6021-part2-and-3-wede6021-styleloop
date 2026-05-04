<?php
// login.php: validates username, email, and password against the hashed password stored in tblUser.
session_start();
include "DBConn.php";
$message = "";
$username = $_POST["username"] ?? "";
$email = $_POST["email"] ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST["password"];
    $stmt = $conn->prepare("SELECT * FROM tblUser WHERE Username = ? AND Email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if ((int)$user["IsVerified"] !== 1) {
            $message = "Your account is still pending administrator verification.";
        } elseif (password_verify($password, $user["PasswordHash"])) {
            $_SESSION["UserID"] = $user["UserID"];
            $_SESSION["FullName"] = $user["FullName"];
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "Incorrect password. Please edit your details and try again.";
        }
    } else {
        $message = "User does not exist. Please register first.";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Login - StyleLoop</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include "header.php"; ?>
<div class="form-page">
    <div class="form-box">
        <div class="brand"><span class="brand-symbol">S</span>StyleLoop</div>
        <h2>Login to StyleLoop</h2>
        <p class="error"><?php echo htmlspecialchars($message); ?></p>
        <form method="POST">
            <label>Username</label><input type="text" name="username" required value="<?php echo htmlspecialchars($username); ?>">
            <label>Email Address</label><input type="email" name="email" required value="<?php echo htmlspecialchars($email); ?>">
            <label>Password</label><input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <p>Do not have an account? <a href="register.php">Register here</a></p>
    </div>
</div>
</body>
</html>
