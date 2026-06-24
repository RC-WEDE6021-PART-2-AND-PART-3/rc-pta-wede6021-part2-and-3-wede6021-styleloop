<?php
session_start();
include "DBConn.php";
include "CartClass.php";
ShoppingCart::ProcessInput();

if (isset($_GET['id'])) { ShoppingCart::AddItem($_GET['id']); }
if (isset($_GET['remove'])) { ShoppingCart::RemoveItem($_GET['remove']); }
if (isset($_POST['emptycart'])) { ShoppingCart::EmptyCart(); }

$checkoutMessage = "";
$referenceNumber = "";

if (isset($_POST['checkout'])) {
    if (!ShoppingCart::Login()) {
        $checkoutMessage = "Please login or register before checkout.";
    } elseif (empty($_SESSION['cart'])) {
        $checkoutMessage = "Your cart is empty.";
    } else {
        $referenceNumber = ShoppingCart::Checkout($conn, (int)$_SESSION['UserID']);
        $checkoutMessage = "Checkout completed successfully. Reference Number: " . $referenceNumber . ". Admin has been notified to confirm delivery and item condition.";
    }
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
        <?php if ($checkoutMessage !== "") { echo "<p class='success'>" . htmlspecialchars($checkoutMessage) . "</p>"; } ?>
        <?php if ($referenceNumber !== "") { echo "<div class='panel-card'><h3>Checkout Reference Number</h3><p><strong>" . htmlspecialchars($referenceNumber) . "</strong></p><p>Your shopping cart has been emptied after checkout.</p></div>"; } ?>
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
                echo "<div><strong>" . htmlspecialchars($item['BrandName']) . " - " . htmlspecialchars($item['ItemName']) . "</strong><br>R" . number_format($item['Price'], 0) . "</div>";
                echo "<strong>R" . number_format($item['Price'], 0) . "</strong>";
                echo "<a href='cart.php?remove=" . $item['ClothesID'] . "'>Remove</a>";
                echo "</div>";
            }
        }
        ?>
        <div class="cart-total">Total: R<?php echo number_format($total, 0); ?> <a class="btn" href="shop.php">Continue Shop</a></div>
        <?php if (!empty($_SESSION['cart'])) { ?>
            <form method="POST" style="text-align:right; margin-top:15px;">
                <button style="width:auto;" name="checkout" value="1" type="submit">Checkout</button>
                <button style="width:auto;" name="emptycart" value="1" type="submit">Empty Cart</button>
            </form>
        <?php } ?>
        <?php if (!empty($_SESSION['cart']) && !isset($_SESSION['UserID'])) { ?>
            <p class="error">You must login or register before checkout.</p>
            <a class="btn" href="login.php">Login</a>
            <a class="btn" href="register.php">Register</a>
        <?php } ?>
    </div>
</main>
</body>
</html>
