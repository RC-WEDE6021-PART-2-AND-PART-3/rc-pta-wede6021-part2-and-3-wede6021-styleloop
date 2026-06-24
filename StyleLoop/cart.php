<?php
session_start();
include "DBConn.php";
if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }
if (isset($_GET['id'])) { $_SESSION['cart'][] = (int)$_GET['id']; }
if (isset($_GET['remove'])) {
    $removeID = (int)$_GET['remove'];
    $_SESSION['cart'] = array_values(array_filter($_SESSION['cart'], fn($cartID) => $cartID !== $removeID));
}
?>
<!DOCTYPE html>
<html>
<head><title>Cart - StyleLoop</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include "header.php"; ?>
<main class="container">
    <div class="cart-box">
        <h2>What is in the Cart → Basket</h2>
        <?php
        $total = 0;
        if (empty($_SESSION['cart'])) { echo "<p>Your cart is currently empty.</p>"; }
        foreach ($_SESSION['cart'] as $id) {
            $stmt = $conn->prepare("SELECT * FROM tblClothes WHERE ClothesID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $item = $stmt->get_result()->fetch_assoc();
            if ($item) {
                $total += $item['Price'];
                echo "<div class='cart-item'>";
                echo "<img src='images/" . htmlspecialchars($item['ImageName']) . "' alt='item'>";
                echo "<div><strong>" . htmlspecialchars($item['ItemName']) . "</strong><br>R" . number_format($item['Price'], 0) . "</div>";
                echo "<strong>R" . number_format($item['Price'], 0) . "</strong>";
                echo "<a href='cart.php?remove=" . $item['ClothesID'] . "'>Remove</a>";
                echo "</div>";
            }
        }
        ?>
        <div class="cart-total">Total: R<?php echo number_format($total, 0); ?> <a class="btn" href="shop.php">Continue Shop</a></div>
    </div>
</main>
</body>
</html>
