<?php
session_start();
include "DBConn.php";
if (!isset($_SESSION['UserID'])) { header("Location: login.php"); exit(); }
$userID = (int)$_SESSION['UserID'];
$stmt = $conn->prepare("SELECT tblOrder.*, tblClothes.BrandName, tblClothes.ItemName, tblClothes.ImageName, tblClothes.Category FROM tblOrder LEFT JOIN tblClothes ON tblOrder.ClothesID = tblClothes.ClothesID WHERE tblOrder.UserID = ? ORDER BY tblOrder.OrderDate DESC");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$totalStmt = $conn->prepare("SELECT COUNT(*) AS PurchaseCount, COALESCE(SUM(TotalAmount),0) AS TotalSpent FROM tblOrder WHERE UserID = ?");
$totalStmt->bind_param("i", $userID);
$totalStmt->execute();
$totals = $totalStmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head><title>Purchase History - StyleLoop</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include "header.php"; ?>
<main class="container">
    <h1>Purchase History Report</h1>
    <div class="panel-card">
        <h2>Totals</h2>
        <p><strong>Total purchases:</strong> <?php echo (int)$totals['PurchaseCount']; ?></p>
        <p><strong>Total spent:</strong> R<?php echo number_format((float)$totals['TotalSpent'], 2); ?></p>
    </div>
    <div class="table-wrap">
        <table>
            <tr><th>Picture</th><th>Reference No.</th><th>Date</th><th>Brand</th><th>Item</th><th>Category</th><th>Amount</th><th>Status</th></tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><img class="table-img" src="images/<?php echo htmlspecialchars($row['ImageName'] ?? 'denim.jpg'); ?>" alt="item"></td>
                <td><?php echo htmlspecialchars($row['ReferenceNumber']); ?></td>
                <td><?php echo htmlspecialchars($row['OrderDate']); ?></td>
                <td><?php echo htmlspecialchars($row['BrandName'] ?? 'Unknown'); ?></td>
                <td><?php echo htmlspecialchars($row['ItemName'] ?? 'Deleted item'); ?></td>
                <td><?php echo htmlspecialchars($row['Category'] ?? 'Unknown'); ?></td>
                <td>R<?php echo number_format($row['TotalAmount'], 2); ?></td>
                <td><?php echo htmlspecialchars($row['OrderStatus']); ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</main>
</body>
</html>
