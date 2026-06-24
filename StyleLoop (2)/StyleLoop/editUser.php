<?php
session_start();
include "DBConn.php";
if (!isset($_SESSION['AdminID'])) { header("Location: adminLogin.php"); exit(); }
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $conn->prepare("SELECT * FROM tblUser WHERE UserID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
if (!$user) { die("User not found."); }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $isVerified = isset($_POST['isverified']) ? 1 : 0;
    $update = $conn->prepare("UPDATE tblUser SET FullName = ?, Email = ?, Username = ?, IsVerified = ? WHERE UserID = ?");
    $update->bind_param("sssii", $fullName, $email, $username, $isVerified, $id);
    $update->execute();
    header("Location: adminDashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head><title>Update User - StyleLoop</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include "header.php"; ?>
<div class="form-page">
    <div class="form-box">
        <h2>Update Customer</h2>
        <form method="POST">
            <label>Full Name</label><input type="text" name="fullname" required value="<?php echo htmlspecialchars($user['FullName']); ?>">
            <label>Email</label><input type="email" name="email" required value="<?php echo htmlspecialchars($user['Email']); ?>">
            <label>Username</label><input type="text" name="username" required value="<?php echo htmlspecialchars($user['Username']); ?>">
            <label><input style="width:auto;" type="checkbox" name="isverified" <?php echo ((int)$user['IsVerified'] === 1) ? 'checked' : ''; ?>> Verified Customer</label>
            <button type="submit">Update Customer</button>
        </form>
    </div>
</div>
</body>
</html>
