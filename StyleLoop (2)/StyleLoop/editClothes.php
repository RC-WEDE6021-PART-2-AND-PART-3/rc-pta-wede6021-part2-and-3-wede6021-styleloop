<?php
session_start();
include "DBConn.php";
if (!isset($_SESSION['AdminID'])) { header("Location: adminLogin.php"); exit(); }
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $conn->prepare("SELECT * FROM tblClothes WHERE ClothesID=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();
if (!$item) { die("Clothing item not found."); }
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = trim($_POST['brandname']);
    $name = trim($_POST['itemname']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $condition = trim($_POST['condition']);
    $size = trim($_POST['size']);
    $image = trim($_POST['imagename']);
    $availability = trim($_POST['availability']);
    $approval = trim($_POST['approval']);
    $delivery = trim($_POST['delivery']);
    $update = $conn->prepare("UPDATE tblClothes SET BrandName=?, ItemName=?, Category=?, Description=?, Price=?, ConditionStatus=?, Size=?, ImageName=?, Availability=?, ApprovalStatus=?, DeliveryStatus=? WHERE ClothesID=?");
    $update->bind_param("ssssdssssssi", $brand, $name, $category, $description, $price, $condition, $size, $image, $availability, $approval, $delivery, $id);
    if ($update->execute()) { header("Location: adminClothes.php"); exit(); } else { $message = "Update failed."; }
}
function sel($a,$b){ return $a===$b ? 'selected' : ''; }
?>
<!DOCTYPE html>
<html>
<head><title>Update Clothing - StyleLoop</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include "header.php"; ?>
<main class="container">
    <div class="form-box" style="margin:25px auto;">
        <h2>Update Clothing Item</h2>
        <p class="error"><?php echo htmlspecialchars($message); ?></p>
        <form method="POST">
            <label>Brand Name</label><input type="text" name="brandname" required value="<?php echo htmlspecialchars($item['BrandName']); ?>">
            <label>Item Name</label><input type="text" name="itemname" required value="<?php echo htmlspecialchars($item['ItemName']); ?>">
            <label>Category</label><select name="category" required>
                <option <?php echo sel('Jackets',$item['Category']); ?>>Jackets</option><option <?php echo sel('Dresses',$item['Category']); ?>>Dresses</option><option <?php echo sel('Shoes',$item['Category']); ?>>Shoes</option><option <?php echo sel('Tops',$item['Category']); ?>>Tops</option><option <?php echo sel('Accessories',$item['Category']); ?>>Accessories</option>
            </select>
            <label>Description</label><textarea name="description" required><?php echo htmlspecialchars($item['Description']); ?></textarea>
            <label>Price</label><input type="number" name="price" min="1" step="0.01" required value="<?php echo htmlspecialchars($item['Price']); ?>">
            <label>Condition</label><select name="condition" required><option <?php echo sel('Excellent',$item['ConditionStatus']); ?>>Excellent</option><option <?php echo sel('Good',$item['ConditionStatus']); ?>>Good</option><option <?php echo sel('Fair',$item['ConditionStatus']); ?>>Fair</option></select>
            <label>Size</label><input type="text" name="size" required value="<?php echo htmlspecialchars($item['Size']); ?>">
            <label>Image File Name</label><input type="text" name="imagename" required value="<?php echo htmlspecialchars($item['ImageName']); ?>">
            <label>Availability</label><select name="availability"><option <?php echo sel('Available',$item['Availability']); ?>>Available</option><option <?php echo sel('Unavailable',$item['Availability']); ?>>Unavailable</option><option <?php echo sel('Sold',$item['Availability']); ?>>Sold</option></select>
            <label>Approval Status</label><select name="approval"><option <?php echo sel('Pending',$item['ApprovalStatus']); ?>>Pending</option><option <?php echo sel('Approved',$item['ApprovalStatus']); ?>>Approved</option><option <?php echo sel('Rejected',$item['ApprovalStatus']); ?>>Rejected</option></select>
            <label>Delivery Status</label><input type="text" name="delivery" value="<?php echo htmlspecialchars($item['DeliveryStatus']); ?>">
            <button type="submit">Save Clothing Updates</button>
        </form>
    </div>
</main>
</body>
</html>
