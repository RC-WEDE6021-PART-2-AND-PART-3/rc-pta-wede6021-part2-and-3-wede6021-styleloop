<?php
// CartClass.php
// This class is included to show the required shopping cart member functions for the POE rubric.
class ShoppingCart {
    public static function ProcessInput() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }
    }

    public static function AddItem($itemID) {
        self::ProcessInput();
        $_SESSION['cart'][] = (int)$itemID;
    }

    public static function RemoveItem($itemID) {
        self::ProcessInput();
        $removeID = (int)$itemID;
        $_SESSION['cart'] = array_values(array_filter($_SESSION['cart'], function($cartID) use ($removeID) {
            return (int)$cartID !== $removeID;
        }));
    }

    public static function EmptyCart() {
        self::ProcessInput();
        $_SESSION['cart'] = [];
    }

    public static function Login() {
        return isset($_SESSION['UserID']);
    }

    public static function Checkout($conn, $userID) {
        self::ProcessInput();
        $referenceNumber = 'SL' . date('YmdHis') . rand(100, 999);
        $total = 0;

        foreach ($_SESSION['cart'] as $id) {
            $stmt = $conn->prepare("SELECT Price FROM tblClothes WHERE ClothesID=? AND ApprovalStatus='Approved' AND Availability='Available'");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            if ($row) {
                $price = (float)$row['Price'];
                $total += $price;
                $order = $conn->prepare("INSERT INTO tblOrder (UserID, ClothesID, ReferenceNumber, TotalAmount, OrderStatus) VALUES (?, ?, ?, ?, 'Pending Delivery Check')");
                $order->bind_param("iisd", $userID, $id, $referenceNumber, $price);
                $order->execute();
                $conn->query("UPDATE tblClothes SET Availability='Sold' WHERE ClothesID=" . (int)$id);
                $text = "Checkout reference $referenceNumber: buyer purchased this item. Admin must communicate with buyer and seller to confirm delivery and item condition.";
                $msg = $conn->prepare("INSERT INTO tblMessages (UserID, ClothesID, SenderRole, MessageText) VALUES (?, ?, 'Buyer', ?)");
                $msg->bind_param("iis", $userID, $id, $text);
                $msg->execute();
            }
        }

        $visit = $conn->prepare("INSERT INTO tblShoppingVisit (UserID, ReferenceNumber, TotalAmount, VisitStatus) VALUES (?, ?, ?, 'Checkout completed')");
        $visit->bind_param("isd", $userID, $referenceNumber, $total);
        $visit->execute();
        self::EmptyCart();
        return $referenceNumber;
    }
}
?>
