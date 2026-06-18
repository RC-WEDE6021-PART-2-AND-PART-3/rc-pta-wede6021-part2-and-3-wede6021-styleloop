<?php
session_start();
include "DBConn.php";
if (!isset($_SESSION['UserID'])) { header("Location: login.php"); exit(); }
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sellerID = (int)$_SESSION['UserID'];
    $brandName = trim($_POST['brandname']);
    $itemName = trim($_POST['itemname']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $condition = trim($_POST['condition']);
    $size = trim($_POST['size']);
    $imageName = trim($_POST['imagename']);
    if ($imageName === '') { $imageName = 'denim.jpg'; }
    $stmt = $conn->prepare("INSERT INTO tblClothes (SellerID, BrandName, ItemName, Category, Description, Price, ConditionStatus, Size, ImageName, ApprovalStatus, Availability) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', 'Available')");
    $stmt->bind_param("issssdsss", $sellerID, $brandName, $itemName, $category, $description, $price, $condition, $size, $imageName);
    if ($stmt->execute()) {
        $newItemID = $stmt->insert_id;
        $requestText = "Seller uploaded a new item request for approval: " . $brandName . " " . $itemName;
        $msg = $conn->prepare("INSERT INTO tblMessages (UserID, ClothesID, SenderRole, MessageText) VALUES (?, ?, 'Seller', ?)");
        $msg->bind_param("iis", $sellerID, $newItemID, $requestText);
        $msg->execute();
        $message = "Your selling request was sent to the administrator. The item will only appear in the shop after admin approval.";
    } else {
        $message = "Upload failed.";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Upload Item - StyleLoop</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include "header.php"; ?>
<main class="container">
    <div class="form-box" style="margin:25px auto;">
        <h2>Send Selling Request</h2>
        <p>Upload your item details. The administrator must approve it before customers can buy it.</p>
        <p class="success"><?php echo htmlspecialchars($message); ?></p>
        <form method="POST">
            <label>Brand Name</label><input type="text" name="brandname" placeholder="e.g. Nike, Zara, H&M" required>
            <label>Item Name</label><input type="text" name="itemname" required>
            <label>Category</label><select name="category" required><option value="">Choose</option><option>Jackets</option><option>Dresses</option><option>Shoes</option><option>Tops</option><option>Accessories</option></select>
            <label>Description</label><textarea name="description" required></textarea>
            <label>Price</label><input type="number" name="price" min="1" step="0.01" required>
            <label>Condition</label><select name="condition" required><option value="">Choose</option><option>Excellent</option><option>Good</option><option>Fair</option></select>
            <label>Size</label><input type="text" name="size" required>
            <label>Image File Name</label><input type="text" name="imagename" placeholder="denim.jpg" required>
            <button type="submit">Send Request To Sell</button>
        </form>
    </div>
</main>
</body>
</html>
