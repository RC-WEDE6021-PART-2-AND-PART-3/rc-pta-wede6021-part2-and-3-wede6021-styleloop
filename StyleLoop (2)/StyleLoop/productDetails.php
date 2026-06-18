<?php
session_start();
include "DBConn.php";
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $conn->prepare("SELECT tblClothes.*, tblUser.FullName FROM tblClothes LEFT JOIN tblUser ON tblClothes.SellerID = tblUser.UserID WHERE ClothesID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();
if (!$item) { die("Item not found."); }
?>
<!DOCTYPE html>
<html>
<head><title><?php echo htmlspecialchars($item['ItemName']); ?></title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include "header.php"; ?>
<main class="container">
    <h1 class="section-title"><?php echo htmlspecialchars($item['ItemName']); ?></h1>
    <div class="product-detail">
        <img src="images/<?php echo htmlspecialchars($item['ImageName']); ?>" alt="<?php echo htmlspecialchars($item['ItemName']); ?>">
        <div>
            <div class="product-price">R<?php echo number_format($item['Price'], 0); ?></div>
            <p><strong>Brand:</strong> <?php echo htmlspecialchars($item['BrandName']); ?></p>
            <p><strong>Category:</strong> <?php echo htmlspecialchars($item['Category']); ?></p>
            <h3>Description</h3>
            <p><?php echo htmlspecialchars($item['Description']); ?></p>
            <p><strong>Size:</strong> <?php echo htmlspecialchars($item['Size']); ?></p>
            <p><strong>Condition:</strong> <?php echo htmlspecialchars($item['ConditionStatus']); ?></p>
            <p><strong>Listed by:</strong> <?php echo htmlspecialchars($item['FullName'] ?? 'StyleLoop Seller'); ?></p>
            <div class="message-preview">💬 Admin can communicate with sellers and buyers to confirm delivery and item condition.</div>
            <a class="btn" onclick="alert('SellPrice: R<?php echo number_format($item['Price'], 0); ?>');" href="addToCart.php?id=<?php echo $item['ClothesID']; ?>&return=cart.php">Add to Cart</a>
            <?php if (isset($_SESSION['UserID'])) { ?>
                <a class="btn secondary" href="messages.php?item=<?php echo $item['ClothesID']; ?>">Message Admin About This Item</a>
            <?php } ?>
        </div>
    </div>
</main>
</body>
</html>
