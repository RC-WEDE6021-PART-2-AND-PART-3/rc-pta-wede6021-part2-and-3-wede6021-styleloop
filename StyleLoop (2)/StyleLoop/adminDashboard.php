<?php
session_start();
include "DBConn.php";
if (!isset($_SESSION['AdminID'])) { header("Location: adminLogin.php"); exit(); }

if (isset($_GET['verify'])) {
    $id = (int)$_GET['verify'];
    $stmt = $conn->prepare("UPDATE tblUser SET IsVerified = 1 WHERE UserID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM tblUser WHERE UserID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
$users = $conn->query("SELECT * FROM tblUser ORDER BY UserID");
$pendingItems = $conn->query("SELECT COUNT(*) AS total FROM tblClothes WHERE ApprovalStatus='Pending'")->fetch_assoc()['total'];
$messages = $conn->query("SELECT COUNT(*) AS total FROM tblMessages WHERE SenderRole IN ('Seller','Buyer')")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html>
<head><title>Admin Dashboard - StyleLoop</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include "header.php"; ?>
<main class="container">
    <h1>Administrator Dashboard</h1>
    <p class="success">Logged in as <?php echo htmlspecialchars($_SESSION['AdminEmail']); ?></p>
    <div class="quick-actions" style="margin-bottom:20px;">
        <a class="quick-box" href="adminClothes.php"><span class="quick-icon">👗</span>Manage Clothing<br><small><?php echo $pendingItems; ?> pending requests</small></a>
        <a class="quick-box" href="adminMessages.php"><span class="quick-icon">💬</span>Seller/Buyer Messages<br><small><?php echo $messages; ?> messages</small></a>
        <a class="quick-box" href="shop.php"><span class="quick-icon">🛍</span>View Shop</a>
    </div>
    <div class="table-wrap">
        <h2>Manage Customers</h2>
        <table>
            <tr><th>User ID</th><th>Full Name</th><th>Email</th><th>Username</th><th>Status</th><th>Actions</th></tr>
            <?php while ($user = $users->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $user['UserID']; ?></td>
                <td><?php echo htmlspecialchars($user['FullName']); ?></td>
                <td><?php echo htmlspecialchars($user['Email']); ?></td>
                <td><?php echo htmlspecialchars($user['Username']); ?></td>
                <td><?php echo ((int)$user['IsVerified'] === 1) ? 'Verified Customer' : 'Pending Approval'; ?></td>
                <td>
                    <?php if ((int)$user['IsVerified'] !== 1) { ?><a class="btn" href="adminDashboard.php?verify=<?php echo $user['UserID']; ?>">Verify</a><?php } ?>
                    <a class="btn" href="editUser.php?id=<?php echo $user['UserID']; ?>">Update</a>
                    <a class="btn" onclick="return confirm('Delete this user?');" href="adminDashboard.php?delete=<?php echo $user['UserID']; ?>">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</main>
</body>
</html>
