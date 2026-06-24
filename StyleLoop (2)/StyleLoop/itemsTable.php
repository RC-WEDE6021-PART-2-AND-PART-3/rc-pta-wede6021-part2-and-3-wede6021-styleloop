<?php
// itemsTable.php
session_start();
include "DBConn.php";
$result = $conn->query("SELECT ClothesID, BrandName, ItemName, Category, Price, ConditionStatus, Availability, ImageName FROM tblClothes WHERE ApprovalStatus='Approved' ORDER BY ClothesID");
$tbl_Item = [];
while ($row = $result->fetch_assoc()) { $tbl_Item[] = $row; }
?>
<!DOCTYPE html>
<html>
<head><title>Item Table - StyleLoop</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include "header.php"; ?>
<main class="container">
    <h1>tbl_Item Associative Array Display</h1>
    <p>This page demonstrates that approved items are read into an associative array and displayed with table headers.</p>
    <?php if (isset($_GET['added'])) { echo "<p class='success'>SellPrice popup displayed. Item added to cart and returned to table.</p>"; } ?>
    <div class="table-wrap">
        <table>
            <tr><th>Picture</th><th>Clothes ID</th><th>Brand</th><th>Item Name</th><th>Category</th><th>Sell Price</th><th>Condition</th><th>Availability</th><th>AddToCart / Cart Picture Button</th></tr>
            <?php foreach ($tbl_Item as $item) { ?>
            <tr>
                <td><img class="table-img" src="images/<?php echo htmlspecialchars($item['ImageName']); ?>" alt="<?php echo htmlspecialchars($item['ItemName']); ?>"></td>
                <td><?php echo htmlspecialchars($item['ClothesID']); ?></td>
                <td><?php echo htmlspecialchars($item['BrandName']); ?></td>
                <td><?php echo htmlspecialchars($item['ItemName']); ?></td>
                <td><?php echo htmlspecialchars($item['Category']); ?></td>
                <td>R<?php echo number_format($item['Price'], 0); ?></td>
                <td><?php echo htmlspecialchars($item['ConditionStatus']); ?></td>
                <td><?php echo htmlspecialchars($item['Availability']); ?></td>
                <td><a class="cart-picture-button" onclick="alert('SellPrice: R<?php echo number_format($item['Price'], 0); ?>');" href="addToCart.php?id=<?php echo $item['ClothesID']; ?>&return=itemsTable.php"><img src="images/<?php echo htmlspecialchars($item['ImageName']); ?>" alt="Cart Button">Add To Cart</a></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</main>
</body>
</html>
