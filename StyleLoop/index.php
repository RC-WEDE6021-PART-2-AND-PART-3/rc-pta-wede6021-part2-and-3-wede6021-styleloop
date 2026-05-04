<?php include "header.php"; include "DBConn.php"; ?>
<!DOCTYPE html>
<html>
<head><title>StyleLoop Home</title><link rel="stylesheet" href="style.css"></head>
<body>
<section class="hero">
    <div>
        <h1>Buy. Sell. Repeat.</h1>
        <p>Post your resale items easily and shop affordable second-hand fashion.</p>
        <a class="btn" href="shop.php">Shop Now</a>
    </div>
    <img src="images/hero.jpg" alt="Folded clothes for StyleLoop">
</section>
<main class="container">
    <h2 class="section-title">Featured Latest Finds</h2>
    <div class="product-grid">
        <?php
        $result = $conn->query("SELECT * FROM tblClothes WHERE Availability = 'Available' ORDER BY ClothesID LIMIT 4");
        while ($item = $result->fetch_assoc()) {
            echo "<div class='card'>";
            echo "<img src='images/" . htmlspecialchars($item['ImageName']) . "' alt='" . htmlspecialchars($item['ItemName']) . "'>";
            echo "<div class='card-body'>";
            echo "<h3>" . htmlspecialchars($item['ItemName']) . "</h3>";
            echo "<div class='price'>R" . number_format($item['Price'], 0) . "</div>";
            echo "<a class='btn' href='productDetails.php?id=" . $item['ClothesID'] . "'>View Details</a>";
            echo "</div></div>";
        }
        ?>
    </div>
</main>
</body>
</html>
