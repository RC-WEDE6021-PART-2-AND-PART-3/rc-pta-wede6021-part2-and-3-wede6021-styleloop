<?php
// loadClothingStore.php
// Run this file first. It drops and recreates the ClothingStore database, all tables, and sample records.

$serverName = "localhost";
$databaseUsername = "root";
$databasePassword = "";

$conn = new mysqli($serverName, $databaseUsername, $databasePassword);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$conn->query("DROP DATABASE IF EXISTS ClothingStore");
$conn->query("CREATE DATABASE ClothingStore");
$conn->select_db("ClothingStore");

$conn->query("CREATE TABLE tblUser (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    FullName VARCHAR(100) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    Username VARCHAR(50) NOT NULL UNIQUE,
    PasswordHash VARCHAR(255) NOT NULL,
    IsVerified TINYINT DEFAULT 0,
    DateRegistered TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$conn->query("CREATE TABLE tblAdmin (
    AdminID INT AUTO_INCREMENT PRIMARY KEY,
    AdminEmail VARCHAR(100) NOT NULL UNIQUE,
    PasswordHash VARCHAR(255) NOT NULL
)");

$conn->query("CREATE TABLE tblClothes (
    ClothesID INT AUTO_INCREMENT PRIMARY KEY,
    SellerID INT NULL,
    ItemName VARCHAR(100) NOT NULL,
    Category VARCHAR(50) NOT NULL,
    Description TEXT NOT NULL,
    Price DECIMAL(10,2) NOT NULL,
    ConditionStatus VARCHAR(50) NOT NULL,
    Size VARCHAR(30) DEFAULT 'Medium',
    ImageName VARCHAR(255) NOT NULL,
    Availability VARCHAR(20) DEFAULT 'Available',
    FOREIGN KEY (SellerID) REFERENCES tblUser(UserID) ON DELETE SET NULL
)");

$conn->query("CREATE TABLE tblOrder (
    OrderID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NULL,
    ClothesID INT NULL,
    OrderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    TotalAmount DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (UserID) REFERENCES tblUser(UserID) ON DELETE SET NULL,
    FOREIGN KEY (ClothesID) REFERENCES tblClothes(ClothesID) ON DELETE SET NULL
)");

$conn->query("CREATE TABLE tblMessages (
    MessageID INT AUTO_INCREMENT PRIMARY KEY,
    SenderID INT NULL,
    ReceiverID INT NULL,
    MessageText TEXT NOT NULL,
    DateSent TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (SenderID) REFERENCES tblUser(UserID) ON DELETE SET NULL,
    FOREIGN KEY (ReceiverID) REFERENCES tblUser(UserID) ON DELETE SET NULL
)");

$adminPassword = password_hash("admin123", PASSWORD_DEFAULT);
$stmtAdmin = $conn->prepare("INSERT INTO tblAdmin (AdminEmail, PasswordHash) VALUES (?, ?)");
$adminEmail = "admin@styleloop.co.za";
$stmtAdmin->bind_param("ss", $adminEmail, $adminPassword);
$stmtAdmin->execute();

$userPassword = password_hash("user123", PASSWORD_DEFAULT);
$users = [
    ["John Doe", "john@gmail.com", "john", 1],
    ["Dineo Makgatho", "dineo@gmail.com", "dineo", 1],
    ["Lerato Mokoena", "lerato@gmail.com", "lerato", 0],
    ["Thabo Molefe", "thabo@gmail.com", "thabo", 1],
    ["Amo Nkosi", "amo@gmail.com", "amo", 0]
];
$stmtUser = $conn->prepare("INSERT INTO tblUser (FullName, Email, Username, PasswordHash, IsVerified) VALUES (?, ?, ?, ?, ?)");
foreach ($users as $user) {
    $stmtUser->bind_param("ssssi", $user[0], $user[1], $user[2], $userPassword, $user[3]);
    $stmtUser->execute();
}

$items = [
    [1, "Vintage Denim Jacket", "Jackets", "Classic vintage denim jacket in great condition. Light blue wash slightly worn look.", 250.00, "Good", "Medium", "denim.jpg"],
    [2, "Floral Summer Dress", "Dresses", "Light floral summer dress, perfect for casual outings.", 180.00, "Excellent", "Small", "dress.jpg"],
    [4, "Brand Name Sneakers", "Shoes", "Comfortable pre-owned sneakers with clean soles.", 450.00, "Good", "Size 6", "sneakers.jpg"],
    [1, "Striped Casual Shirt", "Tops", "Soft striped casual shirt suitable for daily wear.", 150.00, "Good", "Medium", "shirt.jpg"],
    [2, "Leather Handbag", "Accessories", "Brown leather handbag with enough storage space.", 250.00, "Good", "Standard", "handbag.jpg"],
    [4, "Graphic T-Shirt", "Tops", "Graphic T-shirt with comfortable fabric.", 250.00, "Fair", "Large", "tshirt.jpg"],
    [1, "Casual Hoodie", "Jackets", "Warm casual hoodie for cool weather.", 260.00, "Good", "Medium", "hoodie.jpg"],
    [2, "High Heel Boots", "Shoes", "Stylish high heel boots for special occasions.", 450.00, "Excellent", "Size 5", "boots.jpg"]
];
$stmtItem = $conn->prepare("INSERT INTO tblClothes (SellerID, ItemName, Category, Description, Price, ConditionStatus, Size, ImageName) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($items as $item) {
    $stmtItem->bind_param("isssdsss", $item[0], $item[1], $item[2], $item[3], $item[4], $item[5], $item[6], $item[7]);
    $stmtItem->execute();
}

echo "<h2>ClothingStore database created successfully.</h2>";
echo "<p>Admin email: admin@styleloop.co.za</p>";
echo "<p>Admin password: admin123</p>";
echo "<p>Verified user username: john | email: john@gmail.com | password: user123</p>";
echo "<p><a href='index.php'>Open StyleLoop</a></p>";
?>
