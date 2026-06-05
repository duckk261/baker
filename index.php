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
        } elseif ($action == 'login') {
            $userController->login(); // TÔI VỪA BỔ SUNG DÒNG NÀY VÀO ĐÂY
        } elseif ($action == 'update_profile') {
            $userController->updateProfile();
        } elseif ($action == 'forgot') {
            $userController->forgotPassword(); 
        }
    }
}
    if ($action == 'submit_review' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!isset($_SESSION['account_id'])) {
            echo "<script>alert('Vui lòng đăng nhập!'); window.location.href='index.php?page=login';</script>";
            exit();
        }
        $acc_id = $_SESSION['account_id'];
        $prod_id = (int)$_POST['product_id'];
        $order_id = (int)$_POST['order_id'];
        $rating = (int)$_POST['rating'];
        $comment = mysqli_real_escape_string($db, $_POST['comment']);

        mysqli_query($db, "INSERT INTO reviews (account_id, product_id, order_id, rating, comment) VALUES ('$acc_id', '$prod_id', '$order_id', '$rating', '$comment')");
        
        $notif_review_msg = "⭐ Sản phẩm #" . $prod_id . " vừa nhận 1 đánh giá " . $rating . " sao!";
        $notif_review_link = "index.php?page=reviews"; 
        $notif_sql = "INSERT INTO notifications (type, message, link, is_read) VALUES ('review', '$notif_review_msg', '$notif_review_link', 0)";
        mysqli_query($db, $notif_sql);
        
if ($rating >= 4) {
            // Khách chấm 4 hoặc 5 sao
            $msg = "Cảm ơn bạn đã đánh giá! Nhận xét tuyệt vời của bạn là động lực để Baker phát triển hơn nữa.";
        } else {
            // Khách chấm 1, 2 hoặc 3 sao
            $msg = "Baker thành thật xin lỗi vì trải nghiệm chưa tốt của bạn. Chúng tôi đã ghi nhận góp ý và sẽ lập tức cải thiện chất lượng!";
        }
        
        echo "<script>alert('$msg'); window.location.href='index.php?page=history';</script>";
        exit();
    }       
    if ($action == 'toggle_favorite' && isset($_GET['id'])) {
        ob_clean(); 
        header('Content-Type: application/json'); 
        
        if (!isset($_SESSION['account_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Please log in to manage your wishlist!']);
            exit();
        }
        
        $acc_id = $_SESSION['account_id'];
        $prod_id = (int)$_GET['id'];
        
        $check = mysqli_query($db, "SELECT * FROM favorites WHERE account_id = '$acc_id' AND product_id = '$prod_id'");
        if (mysqli_num_rows($check) == 0) {
            mysqli_query($db, "INSERT INTO favorites (account_id, product_id) VALUES ('$acc_id', '$prod_id')");
            echo json_encode(['status' => 'added', 'message' => 'Added to your wishlist!']);
        } else {
            mysqli_query($db, "DELETE FROM favorites WHERE account_id = '$acc_id' AND product_id = '$prod_id'");
            echo json_encode(['status' => 'removed', 'message' => 'Removed from your wishlist!']);
        }
        exit();
    }
    // Xử lý XÓA bánh khi bấm nút thùng rác ở trang Wishlist
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    
    if ($action == 'remove_favorite' && isset($_GET['id'])) {
        if (isset($_SESSION['account_id'])) {
            $fav_id = (int)$_GET['id'];
            $acc_id = $_SESSION['account_id'];
            
            // Xóa đúng cái ID yêu thích đó khỏi Database
            mysqli_query($db, "DELETE FROM favorites WHERE favorite_id = '$fav_id' AND account_id = '$acc_id'");
        }
        
        // Bắt buộc chuyển hướng vòng lại đúng trang Yêu thích (favorites)
        header("Location: index.php?page=favorites");
        exit();
    }
    if ($action == 'submit_contact' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = mysqli_real_escape_string($db, $_POST['name']);
        $email = mysqli_real_escape_string($db, $_POST['email']);
        $phone = mysqli_real_escape_string($db, $_POST['phone']);
        $message = mysqli_real_escape_string($db, $_POST['message']);

mysqli_query($db, "INSERT INTO contacts (name, email, phone, message) VALUES ('$name', '$email', '$phone', '$message')");        echo "<script>alert('Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.'); window.location.href='index.php?page=contact';</script>";
        exit();
    }
    if ($action == 'update_profile' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!isset($_SESSION['account_id'])) {
            echo "<script>window.location.href='index.php?page=login';</script>";
            exit();
        }
        $acc_id = $_SESSION['account_id'];
        
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'] ?? ''; 
        $full_name = trim($last_name . ' ' . $first_name); 
        
        $email = mysqli_real_escape_string($db, $_POST['email']);
        $phone = mysqli_real_escape_string($db, $_POST['phone']);
        $address = mysqli_real_escape_string($db, $_POST['address']);
        
        // Loại bỏ khoảng trắng thừa nếu lỡ gõ dính dấu cách
        $pass_verify = trim($_POST['current_password_verify']); 

        $user_found = false;
        $table_name = '';
        $id_col = '';

        // Danh sách các bảng có thể lưu thông tin
        $queries = [
            ['t' => 'accounts', 'c' => 'account_id'], ['t' => 'accounts', 'c' => 'id'], ['t' => 'accounts', 'c' => 'user_id'],
            ['t' => 'customers', 'c' => 'customer_id'], ['t' => 'customers', 'c' => 'account_id'], ['t' => 'users', 'c' => 'id']
        ];

        // Thuật toán: Tìm đúng người CÓ ID VÀ CÓ PASS KHỚP NHAU
        foreach ($queries as $q) {
            try {
                $res = @mysqli_query($db, "SELECT * FROM {$q['t']} WHERE {$q['c']} = '$acc_id'");
                if ($res && mysqli_num_rows($res) > 0) {
                    while($row = mysqli_fetch_assoc($res)) {
                        $db_pass = $row['password'] ?? $row['pass'] ?? '';
                        // Bao trùm 3 kiểu Pass: Chữ thường, MD5, và Bcrypt
                        if ($pass_verify === $db_pass || md5($pass_verify) === $db_pass || password_verify($pass_verify, $db_pass)) {
                            $user_found = true;
                            $table_name = $q['t'];
                            $id_col = $q['c'];
                            break 2; // Tìm thấy đúng người là thoát vòng lặp ngay
                        }
                    }
                }
            } catch (Exception $e) {}
        }

        if ($user_found) {
            // Bắn lệnh cập nhật thông tin
            try { @mysqli_query($db, "UPDATE $table_name SET account_name = '$full_name', email = '$email', phone = '$phone', address = '$address' WHERE $id_col = '$acc_id'"); } catch(Exception $e) {}
            try { @mysqli_query($db, "UPDATE $table_name SET fullname = '$full_name', email = '$email', phone = '$phone', address = '$address' WHERE $id_col = '$acc_id'"); } catch(Exception $e) {}
            try { @mysqli_query($db, "UPDATE $table_name SET customer_name = '$full_name', email = '$email', phone = '$phone', address = '$address' WHERE $id_col = '$acc_id'"); } catch(Exception $e) {}
            try { @mysqli_query($db, "UPDATE $table_name SET name = '$full_name', email = '$email', phone = '$phone', address = '$address' WHERE $id_col = '$acc_id'"); } catch(Exception $e) {}
            
            $_SESSION['account_name'] = $full_name;
            echo "<script>alert('Cập nhật hồ sơ thành công!'); window.location.href='index.php?page=profile';</script>";
        } else {
            echo "<script>alert('Mật khẩu xác nhận không chính xác! Vui lòng thử lại.'); window.history.back();</script>";
        }
        exit();
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
    case 'process_order':
        require_once 'app/controllers/OrderController.php';
        $orderController = new OrderController($db);
        $orderController->processOrder();
        break;
    case 'order_success':
        require_once 'views/order_success.php';
        break;

    // TÔI VỪA BỔ SUNG CASE NÀY ĐỂ CHỐNG LỖI 404
    case 'user':
        header("Location: index.php?page=login");
        exit();

    case 'login':
        require_once 'views/login.php';
        break;
    case 'register':
        require_once 'views/register.php';
        break;
    case 'forgot_password':
        require_once 'views/forgot_password.php';
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
    case 'product_detail':
        require_once 'views/product_detail.php';
        break;
    case 'favorites':
        if (!isset($_SESSION['account_id'])) {
            header("Location: index.php?page=login");
            exit();
        }
        require_once 'views/favorites.php';
        break;
    case 'history':
        if (!isset($_SESSION['account_id'])) {
            header("Location: index.php?page=login");
            exit();
        }
        require_once 'views/history.php';
        break;
}
?>