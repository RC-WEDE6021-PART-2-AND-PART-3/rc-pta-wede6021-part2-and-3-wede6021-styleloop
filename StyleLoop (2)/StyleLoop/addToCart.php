<?php
// addToCart.php
// Adds a selected clothing item to the session cart and returns to the item table/shop page.
session_start();
if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }

if (isset($_GET['id'])) {
    $clothesID = (int)$_GET['id'];
    if ($clothesID > 0) { $_SESSION['cart'][] = $clothesID; }
}

$returnPage = $_GET['return'] ?? 'itemsTable.php';
$allowedPages = ['itemsTable.php', 'shop.php', 'productDetails.php', 'cart.php'];
if (!in_array($returnPage, $allowedPages)) { $returnPage = 'itemsTable.php'; }
header('Location: ' . $returnPage . '?added=1');
exit();
?>
