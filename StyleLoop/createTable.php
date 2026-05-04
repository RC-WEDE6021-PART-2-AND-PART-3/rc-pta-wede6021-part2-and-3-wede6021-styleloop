<?php
// createTable.php
// This script includes DBConn.php, deletes tblUser if it exists, recreates it, and reloads records from userData.txt.
// The FOREIGN_KEY_CHECKS commands make the script safe to run even after the full database has been created.
include "DBConn.php";

$conn->query("SET FOREIGN_KEY_CHECKS = 0");
$conn->query("DROP TABLE IF EXISTS tblUser");

$conn->query("CREATE TABLE tblUser (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    FullName VARCHAR(100) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    Username VARCHAR(50) NOT NULL UNIQUE,
    PasswordHash VARCHAR(255) NOT NULL,
    IsVerified TINYINT DEFAULT 0,
    DateRegistered TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$file = fopen("userData.txt", "r");
if (!$file) { die("userData.txt could not be opened."); }

$stmt = $conn->prepare("INSERT INTO tblUser (FullName, Email, Username, PasswordHash, IsVerified) VALUES (?, ?, ?, ?, 1)");
while (($line = fgets($file)) !== false) {
    $data = explode(",", trim($line));
    if (count($data) === 4) {
        $fullName = $data[0];
        $email = $data[1];
        $username = $data[2];
        // The text file stores plain sample passwords. The system hashes each password before storing it.
        $passwordHash = password_hash($data[3], PASSWORD_DEFAULT);
        $stmt->bind_param("ssss", $fullName, $email, $username, $passwordHash);
        $stmt->execute();
    }
}
fclose($file);
$conn->query("SET FOREIGN_KEY_CHECKS = 1");

echo "tblUser was deleted, recreated, and loaded correctly from userData.txt.";
?>
