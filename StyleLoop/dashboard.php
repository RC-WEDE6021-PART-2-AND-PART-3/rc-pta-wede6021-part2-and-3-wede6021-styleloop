<?php
session_start();
include "DBConn.php";
if (!isset($_SESSION["UserID"])) { header("Location: login.php"); exit(); }
$userID = (int)$_SESSION["UserID"];
?>
<!DOCTYPE html>
<html>
<head><title>Dashboard - StyleLoop</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include "header.php"; ?>
<div class="dashboard-layout">
    <aside class="sidebar">
        <a class="active" href="dashboard.php">🏠 Dashboard</a>
        <a href="addClothes.php">⬆ Upload Item</a>
        <a href="itemsTable.php">📋 My Listings</a>
        <a href="shop.php">🛍 Shop</a>
        <a href="cart.php">🛒 Cart</a>
        <a href="logout.php">⚙ Logout</a>
    </aside>
    <main class="main-panel">
        <div class="panel-card">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION["FullName"]); ?>!</h1>
            <div class="quick-actions">
                <a class="quick-box" href="dashboard.php"><span class="quick-icon">👜</span>Dashboard</a>
                <a class="quick-box" href="addClothes.php"><span class="quick-icon">📈</span>Upload Item</a>
                <a class="quick-box" href="shop.php"><span class="quick-icon">🛍</span>Shop Items</a>
            </div>
        </div>
        <div class="panel-card">
            <h2>Recent Activity</h2>
            <div class="activity-item"><strong>Someone viewed your listing</strong><span>Now</span></div>
            <div class="activity-item"><strong>New message about an item</strong><span>Today</span></div>
        </div>
        <div class="panel-card">
            <h2>My Uploaded Items</h2>
            <div class="product-grid">
            <?php
            $stmt = $conn->prepare("SELECT * FROM tblClothes WHERE SellerID = ?");
            $stmt->bind_param("i", $userID);
            $stmt->execute();
            $items = $stmt->get_result();
            while ($item = $items->fetch_assoc()) {
                echo "<div class='card'><img src='images/" . htmlspecialchars($item['ImageName']) . "' alt='item'><div class='card-body'>";
                echo "<h3>" . htmlspecialchars($item['ItemName']) . "</h3><div class='price'>R" . number_format($item['Price'], 0) . "</div>";
                echo "<p>" . htmlspecialchars($item['Availability']) . "</p></div></div>";
            }
            ?>
            </div>
        </div>
    </main>
</div>
</body>
</html>
