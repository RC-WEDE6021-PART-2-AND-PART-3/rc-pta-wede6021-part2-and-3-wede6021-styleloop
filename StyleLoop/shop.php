<?php
session_start();
include "DBConn.php";
$category = $_GET['category'] ?? '';
$price = $_GET['price'] ?? '';
$search = $_GET['search'] ?? '';
$query = "SELECT * FROM tblClothes WHERE Availability = 'Available'";
$params = [];
$types = "";
if ($category !== '') { $query .= " AND Category = ?"; $params[] = $category; $types .= "s"; }
if ($price === 'low') { $query .= " AND Price <= 250"; }
if ($price === 'high') { $query .= " AND Price > 250"; }
if ($search !== '') { $query .= " AND ItemName LIKE ?"; $params[] = "%$search%"; $types .= "s"; }
$stmt = $conn->prepare($query);
if (!empty($params)) { $stmt->bind_param($types, ...$params); }
$stmt->execute();
$result = $stmt->get_result();
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
        <select name="category"><option value="">All Categories</option><option value="Jackets">Jackets</option><option value="Dresses">Dresses</option><option value="Shoes">Shoes</option><option value="Tops">Tops</option><option value="Accessories">Accessories</option></select>
        <select name="price"><option value="">Any Price</option><option value="low">R250 or less</option><option value="high">Above R250</option></select>
        <input type="text" name="search" placeholder="Search" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>
    <div class="product-grid">
        <?php while ($item = $result->fetch_assoc()) { ?>
            <div class="card">
                <img src="images/<?php echo htmlspecialchars($item['ImageName']); ?>" alt="<?php echo htmlspecialchars($item['ItemName']); ?>">
                <div class="card-body">
                    <h3><?php echo htmlspecialchars($item['ItemName']); ?></h3>
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
