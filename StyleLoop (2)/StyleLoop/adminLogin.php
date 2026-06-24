<?php
// adminLogin.php: checks the administrator email address and hashed password stored in tblAdmin.
session_start();
include "DBConn.php";
$message = "";
$adminEmail = $_POST['adminemail'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT * FROM tblAdmin WHERE AdminEmail = ?");
    $stmt->bind_param("s", $adminEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['PasswordHash'])) {
            $_SESSION['AdminID'] = $admin['AdminID'];
            $_SESSION['AdminEmail'] = $admin['AdminEmail'];
            header("Location: adminDashboard.php");
            exit();
        } else { $message = "Administrator password is incorrect."; }
    } else { $message = "Administrator email address was not found."; }
}
?>
<!DOCTYPE html>
<html>
<head><title>Admin Login - StyleLoop</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include "header.php"; ?>
<div class="form-page">
    <div class="form-box">
        <div class="brand"><span class="brand-symbol">S</span>StyleLoop</div>
        <h2>Administrator Login</h2>
        <p class="error"><?php echo htmlspecialchars($message); ?></p>
        <form method="POST">
            <label>Admin Email Address</label><input type="email" name="adminemail" required value="<?php echo htmlspecialchars($adminEmail); ?>">
            <label>Password</label><input type="password" name="password" required>
            <button type="submit">Login as Admin</button>
        </form>
    </div>
</div>
</body>
</html>
