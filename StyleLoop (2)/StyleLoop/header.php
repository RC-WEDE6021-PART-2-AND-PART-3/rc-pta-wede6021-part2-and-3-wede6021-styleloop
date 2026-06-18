<?php
// header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentPage = basename($_SERVER['PHP_SELF']);
if (!function_exists('activeLink')) {
    function activeLink($fileName, $currentPage) {
        return $fileName === $currentPage ? 'active' : '';
    }
}
?>
<header class="topbar">
    <a class="brand" href="index.php"><span class="brand-symbol">S</span>StyleLoop</a>
    <nav class="nav-links">
        <a class="<?php echo activeLink('index.php', $currentPage); ?>" href="index.php">Home</a>
        <a class="<?php echo activeLink('shop.php', $currentPage); ?>" href="shop.php">Shop</a>
        <?php if (isset($_SESSION['AdminID'])) { ?>
            <a class="<?php echo activeLink('adminDashboard.php', $currentPage); ?>" href="adminDashboard.php">Admin Dashboard</a>
            <a class="<?php echo activeLink('adminClothes.php', $currentPage); ?>" href="adminClothes.php">Manage Clothing</a>
            <a class="<?php echo activeLink('adminMessages.php', $currentPage); ?>" href="adminMessages.php">Messages</a>
            <a class="<?php echo activeLink('adminReports.php', $currentPage); ?>" href="adminReports.php">Reports</a>
            <a href="logout.php">Logout</a>
        <?php } elseif (isset($_SESSION['UserID'])) { ?>
            <a class="<?php echo activeLink('dashboard.php', $currentPage); ?>" href="dashboard.php">Dashboard</a>
            <a class="<?php echo activeLink('cart.php', $currentPage); ?>" href="cart.php">Cart</a>
            <a class="<?php echo activeLink('messages.php', $currentPage); ?>" href="messages.php">Messages</a>
            <a class="<?php echo activeLink('purchaseHistory.php', $currentPage); ?>" href="purchaseHistory.php">Purchase History</a>
            <a href="logout.php">Logout</a>
        <?php } else { ?>
            <a class="<?php echo activeLink('login.php', $currentPage); ?>" href="login.php">Login</a>
            <a class="<?php echo activeLink('register.php', $currentPage); ?>" href="register.php">Register</a>
            <a class="<?php echo activeLink('adminLogin.php', $currentPage); ?>" href="adminLogin.php">Admin</a>
        <?php } ?>
    </nav>
</header>
