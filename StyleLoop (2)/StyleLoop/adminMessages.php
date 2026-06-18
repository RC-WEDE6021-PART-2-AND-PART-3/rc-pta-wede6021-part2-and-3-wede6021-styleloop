<?php
session_start();
include "DBConn.php";
if (!isset($_SESSION['AdminID'])) { header("Location: adminLogin.php"); exit(); }
$selectedUser = isset($_GET['user']) ? (int)$_GET['user'] : 0;
$selectedItem = isset($_GET['item']) ? (int)$_GET['item'] : 0;
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = (int)$_POST['userid'];
    $clothesID = $_POST['clothesid'] !== '' ? (int)$_POST['clothesid'] : null;
    $text = trim($_POST['message']);
    if ($userID > 0 && $text !== '') {
        $stmt = $conn->prepare("INSERT INTO tblMessages (UserID, ClothesID, SenderRole, MessageText) VALUES (?, ?, 'Admin', ?)");
        $stmt->bind_param("iis", $userID, $clothesID, $text);
        if ($stmt->execute()) { $message = "Admin message sent successfully."; }
    }
}
$users = $conn->query("SELECT UserID, FullName, Email FROM tblUser ORDER BY FullName");
$items = $conn->query("SELECT ClothesID, BrandName, ItemName FROM tblClothes ORDER BY ItemName");
$messages = $conn->query("SELECT tblMessages.*, tblUser.FullName, tblUser.Email, tblClothes.ItemName FROM tblMessages LEFT JOIN tblUser ON tblMessages.UserID=tblUser.UserID LEFT JOIN tblClothes ON tblMessages.ClothesID=tblClothes.ClothesID ORDER BY tblMessages.DateSent DESC");
?>
<!DOCTYPE html>
<html>
<head><title>Admin Messages - StyleLoop</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include "header.php"; ?>
<main class="container">
    <h1>Admin Communication With Sellers and Buyers</h1>
    <p>Use this page to confirm that items are delivered correctly and are in good condition.</p>
    <p class="success"><?php echo htmlspecialchars($message); ?></p>
    <div class="panel-card">
        <form method="POST">
            <label>Select Seller/Buyer</label>
            <select name="userid" required>
                <option value="">Choose user</option>
                <?php while ($u = $users->fetch_assoc()) { ?>
                    <option value="<?php echo $u['UserID']; ?>" <?php echo ($selectedUser === (int)$u['UserID']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($u['FullName'] . ' - ' . $u['Email']); ?></option>
                <?php } ?>
            </select>
            <label>Related Item</label>
            <select name="clothesid">
                <option value="">General</option>
                <?php while ($i = $items->fetch_assoc()) { ?>
                    <option value="<?php echo $i['ClothesID']; ?>" <?php echo ($selectedItem === (int)$i['ClothesID']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($i['BrandName'] . ' - ' . $i['ItemName']); ?></option>
                <?php } ?>
            </select>
            <label>Admin Message</label>
            <textarea name="message" required placeholder="Example: Please confirm that this item was delivered and received in good condition."></textarea>
            <button type="submit">Send Message</button>
        </form>
    </div>
    <div class="table-wrap">
        <h2>All Seller and Buyer Communication</h2>
        <table><tr><th>Date</th><th>User</th><th>Role</th><th>Item</th><th>Message</th><th>Reply</th></tr>
        <?php while ($row = $messages->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['DateSent']); ?></td>
                <td><?php echo htmlspecialchars(($row['FullName'] ?? 'Unknown') . ' ' . ($row['Email'] ?? '')); ?></td>
                <td><?php echo htmlspecialchars($row['SenderRole']); ?></td>
                <td><?php echo htmlspecialchars($row['ItemName'] ?? 'General'); ?></td>
                <td><?php echo htmlspecialchars($row['MessageText']); ?></td>
                <td><a class="btn" href="adminMessages.php?user=<?php echo (int)$row['UserID']; ?>&item=<?php echo (int)$row['ClothesID']; ?>">Reply</a></td>
            </tr>
        <?php } ?>
        </table>
    </div>
</main>
</body>
</html>
