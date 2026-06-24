<?php
session_start();
include "DBConn.php";
$category = $_GET['category'] ?? '';
$price = $_GET['price'] ?? '';
$search = $_GET['search'] ?? '';
$query = "SELECT * FROM tblClothes WHERE Availability = 'Available' AND ApprovalStatus = 'Approved'";
$params = [];
$types = "";
if ($category !== '') { $query .= " AND Category = ?"; $params[] = $category; $types .= "s"; }
if ($price === 'under200') { $query .= " AND Price <= 200"; }
if ($price === '200to300') { $query .= " AND Price > 200 AND Price <= 300"; }
if ($price === 'above300') { $query .= " AND Price > 300"; }
if ($search !== '') { $query .= " AND (ItemName LIKE ? OR BrandName LIKE ? OR Description LIKE ?)"; $like = "%$search%"; $params[] = $like; $params[] = $like; $params[] = $like; $types .= "sss"; }
$query .= " ORDER BY ClothesID DESC";
$stmt = $conn->prepare($query);
if (!empty($params)) { $stmt->bind_param($types, ...$params); }
$stmt->execute();
$result = $stmt->get_result();
function selectedOption($value, $current) { return $value === $current ? 'selected' : ''; }
?>
<!DOCTYPE html>
<html>
<head><title>Shop - StyleLoop</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include "header.php"; ?>
<main class="container">
    <h1 class="section-title">Product Listing</h1>
    <?php if (isset($_GET['added'])) { echo "<p class='success'>Item was added to cart and the SellPrice popup was displayed.</p>"; } ?>
    <form class="filter-bar" method="GET">
        <select name="category">
            <option value="">All Categories</option>
            <option value="Jackets" <?php echo selectedOption('Jackets', $category); ?>>Jackets</option>
            <option value="Dresses" <?php echo selectedOption('Dresses', $category); ?>>Dresses</option>
            <option value="Shoes" <?php echo selectedOption('Shoes', $category); ?>>Shoes</option>
            <option value="Tops" <?php echo selectedOption('Tops', $category); ?>>Tops</option>
            <option value="Accessories" <?php echo selectedOption('Accessories', $category); ?>>Accessories</option>
        </select>
        <select name="price">
            <option value="">Any Price</option>
            <option value="under200" <?php echo selectedOption('under200', $price); ?>>R200 or less</option>
            <option value="200to300" <?php echo selectedOption('200to300', $price); ?>>R201 - R300</option>
            <option value="above300" <?php echo selectedOption('above300', $price); ?>>Above R300</option>
        </select>
        <input type="text" name="search" placeholder="Search brand or item" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>
    <?php if ($result->num_rows === 0) { echo "<p class='error'>No items match the selected category and price filter.</p>"; } ?>
    <div class="product-grid">
        <?php while ($item = $result->fetch_assoc()) { ?>
            <div class="card">
                <img src="images/<?php echo htmlspecialchars($item['ImageName']); ?>" alt="<?php echo htmlspecialchars($item['ItemName']); ?>">
                <div class="card-body">
                    <h3><?php echo htmlspecialchars($item['ItemName']); ?></h3>
                    <p><strong>Brand:</strong> <?php echo htmlspecialchars($item['BrandName']); ?></p>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($item['Category']); ?></p>
                    <div class="price">R<?php echo number_format($item['Price'], 0); ?></div>
                    <a class="btn" href="productDetails.php?id=<?php echo $item['ClothesID']; ?>">View Details</a>
                    <br><br>
                    <a class="cart-button" onclick="alert('SellPrice: R<?php echo number_format($item['Price'], 0); ?>');" href="addToCart.php?id=<?php echo $item['ClothesID']; ?>&return=shop.php"><img class="add-cart-img" src="images/<?php echo htmlspecialchars($item['ImageName']); ?>" alt="cart">Add To Cart</a>
                </div>
            </div>
        <?php } ?>
    </div>
</main>
</body>
</html>
