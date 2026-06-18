<?php
session_start();
include "DBConn.php";
if (!isset($_SESSION['AdminID'])) { header("Location: adminLogin.php"); exit(); }
$summary = $conn->query("SELECT COUNT(*) AS OrdersCount, COALESCE(SUM(TotalAmount),0) AS SalesTotal FROM tblOrder")->fetch_assoc();
$orders = $conn->query("SELECT tblOrder.*, tblUser.FullName, tblUser.Email, tblClothes.BrandName, tblClothes.ItemName FROM tblOrder LEFT JOIN tblUser ON tblOrder.UserID=tblUser.UserID LEFT JOIN tblClothes ON tblOrder.ClothesID=tblClothes.ClothesID ORDER BY tblOrder.OrderDate DESC");
$visits = $conn->query("SELECT tblShoppingVisit.*, tblUser.FullName FROM tblShoppingVisit LEFT JOIN tblUser ON tblShoppingVisit.UserID=tblUser.UserID ORDER BY tblShoppingVisit.VisitDate DESC");
?>
<!DOCTYPE html>
<html>
<head><title>Admin Reports - StyleLoop</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include "header.php"; ?>
<main class="container">
    <h1>Admin Purchase and Shopping Visits Report</h1>
    <div class="panel-card">
        <h2>Overall Totals</h2>
        <p><strong>Total orders:</strong> <?php echo (int)$summary['OrdersCount']; ?></p>
        <p><strong>Total sales:</strong> R<?php echo number_format((float)$summary['SalesTotal'], 2); ?></p>
    </div>
    <h2>Purchase Report</h2>
    <div class="table-wrap"><table>
        <tr><th>Reference No.</th><th>Date</th><th>Buyer</th><th>Email</th><th>Brand</th><th>Item</th><th>Amount</th><th>Status</th></tr>
        <?php while ($row = $orders->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['ReferenceNumber']); ?></td>
            <td><?php echo htmlspecialchars($row['OrderDate']); ?></td>
            <td><?php echo htmlspecialchars($row['FullName'] ?? 'Unknown'); ?></td>
            <td><?php echo htmlspecialchars($row['Email'] ?? 'Unknown'); ?></td>
            <td><?php echo htmlspecialchars($row['BrandName'] ?? 'Unknown'); ?></td>
            <td><?php echo htmlspecialchars($row['ItemName'] ?? 'Deleted item'); ?></td>
            <td>R<?php echo number_format($row['TotalAmount'], 2); ?></td>
            <td><?php echo htmlspecialchars($row['OrderStatus']); ?></td>
        </tr>
        <?php } ?>
    </table></div>
    <h2>Shopping Visits Written Into Database</h2>
    <div class="table-wrap"><table>
        <tr><th>Visit ID</th><th>User</th><th>Reference No.</th><th>Total</th><th>Status</th><th>Date</th></tr>
        <?php while ($v = $visits->fetch_assoc()) { ?>
        <tr>
            <td><?php echo (int)$v['VisitID']; ?></td>
            <td><?php echo htmlspecialchars($v['FullName'] ?? 'Unknown'); ?></td>
            <td><?php echo htmlspecialchars($v['ReferenceNumber']); ?></td>
            <td>R<?php echo number_format($v['TotalAmount'], 2); ?></td>
            <td><?php echo htmlspecialchars($v['VisitStatus']); ?></td>
            <td><?php echo htmlspecialchars($v['VisitDate']); ?></td>
        </tr>
        <?php } ?>
    </table></div>
</main>
</body>
</html>
