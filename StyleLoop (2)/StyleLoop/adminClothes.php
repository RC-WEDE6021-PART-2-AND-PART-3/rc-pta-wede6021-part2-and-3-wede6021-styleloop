<?php
session_start();
include "DBConn.php";
if (!isset($_SESSION['AdminID'])) { header("Location: adminLogin.php"); exit(); }
if (isset($_GET['approve'])) {
    $id = (int)$_GET['approve'];
    $stmt = $conn->prepare("UPDATE tblClothes SET ApprovalStatus='Approved', Availability='Available' WHERE ClothesID=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
if (isset($_GET['reject'])) {
    $id = (int)$_GET['reject'];
    $stmt = $conn->prepare("UPDATE tblClothes SET ApprovalStatus='Rejected', Availability='Unavailable' WHERE ClothesID=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM tblClothes WHERE ClothesID=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
$result = $conn->query("SELECT tblClothes.*, tblUser.FullName FROM tblClothes LEFT JOIN tblUser ON tblClothes.SellerID=tblUser.UserID ORDER BY ApprovalStatus='Pending' DESC, ClothesID DESC");
?>
<!DOCTYPE html>
<html>
<head><title>Manage Clothing - StyleLoop</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include "header.php"; ?>
<main class="container">
    <h1>Manage Clothing and Seller Requests</h1>
    <p>Approve seller item requests, update clothing details, or remove incorrect listings.</p>
    <div class="table-wrap">
        <table>
            <tr><th>Picture</th><th>Brand</th><th>Item</th><th>Seller</th><th>Category</th><th>Price</th><th>Condition</th><th>Status</th><th>Actions</th></tr>
            <?php while ($item = $result->fetch_assoc()) { ?>
            <tr>
                <td><img class="table-img" src="images/<?php echo htmlspecialchars($item['ImageName']); ?>" alt="item"></td>
                <td><?php echo htmlspecialchars($item['BrandName']); ?></td>
                <td><?php echo htmlspecialchars($item['ItemName']); ?></td>
                <td><?php echo htmlspecialchars($item['FullName'] ?? 'Unknown Seller'); ?></td>
                <td><?php echo htmlspecialchars($item['Category']); ?></td>
                <td>R<?php echo number_format($item['Price'], 0); ?></td>
                <td><?php echo htmlspecialchars($item['ConditionStatus']); ?></td>
                <td><?php echo htmlspecialchars($item['ApprovalStatus']); ?></td>
                <td>
                    <?php if ($item['ApprovalStatus'] !== 'Approved') { ?><a class="btn" href="adminClothes.php?approve=<?php echo $item['ClothesID']; ?>">Approve</a><?php } ?>
                    <?php if ($item['ApprovalStatus'] !== 'Rejected') { ?><a class="btn" href="adminClothes.php?reject=<?php echo $item['ClothesID']; ?>">Reject</a><?php } ?>
                    <a class="btn" href="editClothes.php?id=<?php echo $item['ClothesID']; ?>">Update</a>
                    <a class="btn" href="adminMessages.php?user=<?php echo (int)$item['SellerID']; ?>&item=<?php echo $item['ClothesID']; ?>">Message Seller</a>
                    <a class="btn" onclick="return confirm('Delete this clothing item?');" href="adminClothes.php?delete=<?php echo $item['ClothesID']; ?>">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</main>
</body>
</html>
