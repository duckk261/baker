<?php
// MOCK SESSION
$_SESSION = [];
$_SESSION['account_id'] = 13;
$_SESSION['cart'] = [1, 2]; // Assuming products 1 and 2 exist

$_SERVER['REQUEST_METHOD'] = 'POST';

$_POST['fullname'] = 'Test User';
$_POST['email'] = 'test@example.com';
$_POST['phone'] = '0123456789';
$_POST['address'] = 'Test Address';
$_POST['payment'] = 'COD';

require_once 'app/classes/Database.php';
$db = Database::getInstance();

require_once 'app/controllers/OrderController.php';
$controller = new OrderController($db);

try {
    $controller->processOrder();
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
echo "Done.\n";
?>
