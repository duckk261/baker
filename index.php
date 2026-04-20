<?php
session_start();

require_once 'app/classes/Database.php';
$db = Database::getInstance();


$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : '';


if ($action !== '') {
    if ($page == 'cart') {
        require_once 'app/controllers/CartController.php';
        $cartController = new CartController($db);
        
        if ($action == 'add') {
            $cartController->addToCart(); 
        } elseif ($action == 'update') {
            $cartController->updateCart();
        }
    }
    
    // Xử lý Người dùng
    if ($page == 'user') {
        require_once 'app/controllers/UserController.php';
        $userController = new UserController($db);
        
        if ($action == 'register') {
            $userController->register();
        } elseif ($action == 'update_profile') {
            $userController->updateProfile();
        }
    }
}
switch ($page) {
    case 'home':
        require_once 'views/home.php';
        break;
case 'about':
        require_once 'views/about.php';
        break;

    case 'contact':
        require_once 'views/contact.php';
        break;
    case 'product':
        require_once 'views/product.php';
        break;

   case 'cart':

        require_once 'app/controllers/CartController.php';
        $cartController = new CartController($db);
        $cart_data = $cartController->getCartDetails();
        $cart_details = $cart_data['details'];
        $subtotal = $cart_data['subtotal'];
        $tax_amount = $cart_data['tax_amount'];
        $shipping = $cart_data['shipping'];
        $final_total = $cart_data['final_total'];
        require_once 'views/cart.php';
        break;

    case 'checkout':
        if (empty($_SESSION['cart'])) {
            echo "<script>alert('Giỏ hàng trống!'); window.location.href='index.php?page=product';</script>";
            exit();
        }
        require_once 'views/checkout.php';
        break;

    case 'login':
        require_once 'views/login.php';
        break;

    case 'register':
        require_once 'views/register.php';
        break;

    case 'profile':
        if (!isset($_SESSION['account_id'])) {
            header("Location: index.php?page=login");
            exit();
        }
        require_once 'views/profile.php';
        break;

    case 'logout':
        session_unset();
        session_destroy();
        header("Location: index.php?page=home");
        exit();

    default:
        echo "<div style='text-align:center; padding: 100px;'>
                <h1 style='color:red;'>404 - KHÔNG TÌM THẤY TRANG</h1>
                <a href='index.php?page=home'>Quay lại trang chủ</a>
              </div>";
        break;
}
?>