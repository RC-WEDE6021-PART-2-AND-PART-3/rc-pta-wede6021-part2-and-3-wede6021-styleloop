<?php
session_start();
include "DBConn.php";
if (!isset($_SESSION['UserID'])) { header("Location: login.php"); exit(); }
$userID = (int)$_SESSION['UserID'];
$itemID = isset($_GET['item']) ? (int)$_GET['item'] : null;
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemIDPost = $_POST['clothesid'] !== '' ? (int)$_POST['clothesid'] : null;
    $text = trim($_POST['message']);
    if ($text !== '') {
        $stmt = $conn->prepare("INSERT INTO tblMessages (UserID, ClothesID, SenderRole, MessageText) VALUES (?, ?, 'Buyer', ?)");
        $stmt->bind_param("iis", $userID, $itemIDPost, $text);
        if ($stmt->execute()) { $message = "Message sent to administrator."; }
    }
}
$items = $conn->query("SELECT ClothesID, ItemName, BrandName FROM tblClothes WHERE ApprovalStatus='Approved' ORDER BY ItemName");
$stmt = $conn->prepare("SELECT tblMessages.*, tblClothes.ItemName FROM tblMessages LEFT JOIN tblClothes ON tblMessages.ClothesID=tblClothes.ClothesID WHERE tblMessages.UserID=? ORDER BY DateSent DESC");
$stmt->bind_param("i", $userID);
$stmt->execute();
$messages = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head><title>Messages - StyleLoop</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include "header.php"; ?>
<main class="container">
    <h1>Communicate With Administrator</h1>
    <p>Use this page to ask the administrator about delivery, item condition, or selling requests.</p>
    <p class="success"><?php echo htmlspecialchars($message); ?></p>
    <div class="panel-card">
        <form method="POST">
            <label>Related Item</label>
            <select name="clothesid">
                <option value="">General message</option>
                <?php while ($item = $items->fetch_assoc()) { ?>
                    <option value="<?php echo $item['ClothesID']; ?>" <?php echo ($itemID === (int)$item['ClothesID']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($item['BrandName'] . ' - ' . $item['ItemName']); ?></option>
                <?php } ?>
            </select>
            <label>Message</label>
            <textarea name="message" required placeholder="Example: Please confirm delivery and item condition."></textarea>
            <button type="submit">Send Message</button>
        </form>
    </div>
    <div class="table-wrap">
        <h2>My Messages</h2>
        <table><tr><th>Date</th><th>Role</th><th>Item</th><th>Message</th></tr>
        <?php while ($row = $messages->fetch_assoc()) { ?>
            <tr><td><?php echo htmlspecialchars($row['DateSent']); ?></td><td><?php echo htmlspecialchars($row['SenderRole']); ?></td><td><?php echo htmlspecialchars($row['ItemName'] ?? 'General'); ?></td><td><?php echo htmlspecialchars($row['MessageText']); ?></td></tr>
        <?php } ?>
        </table>
    </div>
</main>
</body>
</html>
