<?php
ob_start();
session_start();

// 1. KIỂM TRA QUYỀN ADMIN
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
    echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Access Denied! Bạn không có quyền truy cập khu vực này.\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.location.href = '../index.php'; });});</script>";
    exit();
}

require_once '../app/classes/Database.php';
$db = Database::getInstance();
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

if (isset($_GET['action'])) {
    
    if ($_GET['action'] == 'approve' && isset($_GET['id'])) {
        $approve_id = mysqli_real_escape_string($db, $_GET['id']);
        mysqli_query($db, "UPDATE orders SET status = 'Dang_giao' WHERE order_id = '$approve_id'");
        $back_page = isset($_GET['back']) ? $_GET['back'] : 'orders';
        echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Duyệt thành công! Đơn hàng đang được vận chuyển.\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.location.href = 'index.php?page=$back_page'; });});</script>";
        exit();
    }
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id']) && $page == 'accounts') {
    $del_id = mysqli_real_escape_string($db, $_GET['id']);
    // Xóa tài khoản
    mysqli_query($db, "DELETE FROM accounts WHERE customer_id = '$del_id'");
    echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Đã xóa tài khoản!\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.location.href = 'index.php?page=accounts'; });});</script>";
    exit();
}
    elseif ($_GET['action'] == 'complete' && isset($_GET['id'])) {
        $complete_id = mysqli_real_escape_string($db, $_GET['id']);
        mysqli_query($db, "UPDATE orders SET status = 'Hoan_tat' WHERE order_id = '$complete_id'");
        $back_page = isset($_GET['back']) ? $_GET['back'] : 'orders';
        echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Đơn hàng đã giao thành công và hoàn tất!\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.location.href = 'index.php?page=$back_page'; });});</script>";
        exit();
    }
    elseif ($_GET['action'] == 'delete_product' && isset($_GET['id'])) {
        $del_id = mysqli_real_escape_string($db, $_GET['id']);
        mysqli_query($db, "DELETE FROM cart WHERE product_id = '$del_id'");
        mysqli_query($db, "DELETE FROM orderdetails WHERE product_id = '$del_id'");
        if (mysqli_query($db, "DELETE FROM products WHERE product_id = '$del_id'")) {
            echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Đã xóa sản phẩm!\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.location.href = 'index.php?page=products'; });});</script>";
        } else {
            echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Lỗi hệ thống: Không thể xóa sản phẩm này.\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.location.href = 'index.php?page=products'; });});</script>";
        }
        exit();
    }
    elseif ($_GET['action'] == 'delete_category' && isset($_GET['id'])) {
        $del_cat_id = mysqli_real_escape_string($db, $_GET['id']);
        $check_products = @mysqli_query($db, "SELECT COUNT(*) as total FROM products WHERE category_id = '$del_cat_id'");
        $row = mysqli_fetch_assoc($check_products);
        
        if ($row['total'] > 0) {
            echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Không thể xóa!\", text: \"Danh mục này đang chứa " . $row['total'] . " sản phẩm. Vui lòng chuyển hoặc xóa các sản phẩm đó trước.\", confirmButtonColor: \"#d33\", icon: \"error\"}).then((result) => { window.location.href = 'index.php?page=categories'; });});</script>";
            exit(); 
        }

        $deleted = @mysqli_query($db, "DELETE FROM categories WHERE category_id = '$del_cat_id'");
        if (!$deleted) {
            $deleted = @mysqli_query($db, "DELETE FROM categories WHERE id = '$del_cat_id'");
        }

        if ($deleted) {
            echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thành công\", text: \"Đã xóa danh mục an toàn!\", confirmButtonColor: \"#c4a16b\", icon: \"success\"}).then((result) => { window.location.href = 'index.php?page=categories'; });});</script>";
        } else {
            echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Lỗi hệ thống\", text: \"Error: Cannot delete this category.\", confirmButtonColor: \"#d33\", icon: \"error\"}).then((result) => { window.location.href = 'index.php?page=categories'; });});</script>";
        }
        exit();
    }
    elseif ($_GET['action'] == 'delete_review' && isset($_GET['id'])) {
        $del_id = mysqli_real_escape_string($db, $_GET['id']);
        mysqli_query($db, "DELETE FROM reviews WHERE review_id = '$del_id'");
        
        // Dùng REFERER để tự động quay lại đúng trang chi tiết sản phẩm hiện tại
        $ref = $_SERVER['HTTP_REFERER'] ?? 'index.php?page=reviews';
        echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Đã xóa bình luận này khỏi hệ thống!\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.location.href = '$ref'; });});</script>";
        exit();
    }
    elseif ($_GET['action'] == 'toggle_review' && isset($_GET['id'])) {
        $toggle_id = mysqli_real_escape_string($db, $_GET['id']);
        
        $res = mysqli_query($db, "SELECT status FROM reviews WHERE review_id = '$toggle_id'");
        if ($res && mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            $new_status = ($row['status'] == 1) ? 0 : 1; 
            mysqli_query($db, "UPDATE reviews SET status = '$new_status' WHERE review_id = '$toggle_id'");
        }
        
        // Quay lại đúng vị trí cũ mượt mà không cần load sang trang khác
        $ref = $_SERVER['HTTP_REFERER'] ?? 'index.php?page=reviews';
        header("Location: " . $ref);
        exit();
    }
} // Đóng khối if (isset($_GET['action']))


$total_customers = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as count FROM customers"))['count'] ?? 0;
$total_products = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as count FROM products"))['count'] ?? 0;
$total_orders = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as count FROM orders"))['count'] ?? 0;
$revenue_query = mysqli_query($db, "SELECT SUM(total_amount) as total FROM orders WHERE status = 'Completed' OR status = 'Đã giao' OR status = 'Hoàn thành' OR status = 'Hoan_tat'");
$total_revenue = mysqli_fetch_assoc($revenue_query)['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Baker Store - Quản Trị Hệ Thống</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Segoe+UI:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; }
        .sidebar { height: 100vh; background-color: #343a40; padding-top: 20px; color: white; position: fixed; width: 250px; z-index: 1000;}
        .sidebar a { color: #c2c7d0; text-decoration: none; padding: 15px 20px; display: block; font-weight: 500; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background-color: #c4a16b; color: white; border-left: 4px solid #fff; }
        .sidebar .brand { font-size: 1.5rem; font-weight: bold; text-align: center; margin-bottom: 30px; color: #eab676; font-family: 'Playfair Display', serif;}
        .main-content { margin-left: 250px; padding: 30px; }
        .card-stat { border-radius: 10px; border: none; transition: 0.3s; cursor: pointer; }
        .card-stat:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important; }
        .icon-large { font-size: 3rem; opacity: 0.7; }
        .table-hover tbody tr:hover { background-color: #f1f3f5; }

        .main-content h1, .main-content h2, .main-content h3, .main-content .display-6 { 
            font-family: 'Playfair Display', serif !important; 
            font-weight: 700 !important; 
            color: #212529 !important;
        }

        .main-content table thead,
        .main-content table thead tr,
        .main-content table thead th {
            background-color: #f8f9fa !important;
            color: #333333 !important;
            font-weight: 600 !important;
            border-bottom: 2px solid #dee2e6 !important;
            text-transform: none !important;
            padding-top: 15px !important;
            padding-bottom: 15px !important;
        }

        /* 3. Chuẩn hóa màu sắc nút bấm và text primary sang tone màu tiệm bánh */
        .btn-primary, .bg-primary, .btn-primary:active { background-color: #c4a16b !important; border-color: #c4a16b !important; color: #fff !important;}
        .btn-primary:hover { background-color: #b08d55 !important; border-color: #b08d55 !important; }
        .text-primary { color: #c4a16b !important; }
        .border-primary { border-color: #c4a16b !important; }
        .badge.bg-primary { background-color: #c4a16b !important; }
        .card-stat,
        .card-stat h2,
        .card-stat h3,
        .card-stat p,
        .card-stat span,
        .card-stat div,
        .card-stat i {
            color: #ffffff !important;
        }
    </style>

<script>
function confirmAction(event, url, message) {
    event.preventDefault();
    Swal.fire({
        title: 'Xác nhận',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#c4a16b',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Đồng ý',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}
</script>
</head>
<body>

    <div class="sidebar shadow">
        <div class="brand"><i class="fas fa-bread-slice me-2"></i> Quản Trị Baker</div>
        <a href="index.php?page=dashboard" class="<?php echo ($page == 'dashboard') ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt me-2"></i>Bảng Điều Khiển</a>
        <a href="index.php?page=products" class="<?php echo ($page == 'products' || $page == 'product_detail' || $page == 'edit_product' || $page == 'add_product') ? 'active' : ''; ?>"><i class="fas fa-box-open me-2"></i>Sản Phẩm</a>
        <a href="index.php?page=categories" class="<?php echo ($page == 'categories') ? 'active' : ''; ?>"><i class="fas fa-list me-2"></i>Danh Mục</a>
        <a href="index.php?page=orders" class="<?php echo ($page == 'orders' || $page == 'order_detail') ? 'active' : ''; ?>"><i class="fas fa-shopping-cart me-2"></i>Đơn Hàng</a>
        <a href="index.php?page=customers" class="<?php echo ($page == 'customers') ? 'active' : ''; ?>"><i class="fas fa-users me-2"></i> Khách Hàng</a>
        <a href="index.php?page=reviews" class="<?php echo ($page == 'reviews' || $page == 'review_detail') ? 'active' : ''; ?>"><i class="fas fa-star me-2"></i>Đánh Giá</a>
        <a href="index.php?page=contacts" class="<?php echo ($page == 'contacts') ? 'active' : ''; ?>"><i class="fas fa-comments me-2"></i>Liên Hệ</a>
        <a href="index.php?page=accounts" class="<?php echo ($page == 'accounts') ? 'active' : ''; ?>"><i class="fas fa-user-tie me-2"></i>Tài Khoản</a>
        <hr style="border-color: #666; margin: 20px;">
        <a href="../index.php" class="text-warning"><i class="fas fa-store me-2"></i> Về Cửa Hàng</a>
        <a href="../index.php?page=logout" class="text-danger"><i class="fas fa-sign-out-alt me-2"></i>Đăng Xuất</a>
    </div>
    <div class="main-content">
        <div class="d-flex justify-content-end mb-3">
            <div class="dropdown">
                <button class="btn btn-white position-relative border-0 shadow-sm rounded-circle d-flex align-items-center justify-content-center" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 45px; height: 45px; background: #fff;">
                    <i class="fas fa-bell fs-5 text-warning"></i>
                    <span id="notif-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm" style="display: none; font-size: 0.7rem;">0</span>
                </button>
                
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-0" id="notif-list" style="width: 320px; max-height: 400px; overflow-y: auto; border-radius: 10px;">
                    <li><h6 class="dropdown-header fw-bold text-uppercase bg-light py-3 border-bottom">🔔 Thông báo mới</h6></li>
                    </ul>
            </div>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    function fetchNotifications() {
        // Nhớ đảm bảo file get_notifications.php nằm cùng thư mục admin nhé
        fetch('get_notifications.php')
            .then(response => response.json())
            .then(data => {
                // 1. Cập nhật con số ở chấm đỏ
                const badge = document.getElementById('notif-badge');
                if (data.unread > 0) {
                    badge.innerText = data.unread;
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }

                // 2. Cập nhật danh sách thả xuống
                const list = document.getElementById('notif-list');
                let html = '<li><h6 class="dropdown-header fw-bold text-uppercase bg-light py-3 border-bottom">🔔 Thông báo mới</h6></li>';
                
                if (data.data.length > 0) {
                    data.data.forEach(item => {
                        let unreadClass = item.is_read == 0 ? 'bg-light fw-bold' : '';
                        let icon = item.type == 'new_order' ? '<i class="fas fa-cart-plus text-success me-2"></i>' : 
                                  (item.type == 'cancel' ? '<i class="fas fa-ban text-danger me-2"></i>' : '<i class="fas fa-star text-warning me-2"></i>');
                        
                        html += `
                            <li>
                                <a class="dropdown-item border-bottom py-3 text-wrap ${unreadClass}" href="${item.link}" style="font-size: 0.9rem;">
                                    <div>${icon} ${item.message}</div>
                                    <div class="text-muted mt-2 text-end" style="font-size: 0.75rem;"><i class="far fa-clock me-1"></i>${item.created_at}</div>
                                </a>
                            </li>`;
                    });
                } else {
                    html += '<li><span class="dropdown-item text-muted text-center py-4">Không có thông báo nào</span></li>';
                }
                list.innerHTML = html;
            })
            .catch(error => console.error('Lỗi load thông báo:', error));
    }

    // Chạy lần đầu khi vừa mở admin
    fetchNotifications();

    // Tự động quét lại mỗi 5 giây
    setInterval(fetchNotifications, 5000);
    </script>
      <?php if ($page == 'dashboard'): ?>
            <?php
            require_once '../app/controllers/AdminDashboardController.php';
            $adminDashboardController = new AdminDashboardController($db);
            $adminDashboardController->index();
            ?>

      <?php elseif ($page == 'products'): ?>
            <?php
            require_once '../app/controllers/AdminProductController.php';
            $adminProductController = new AdminProductController($db);
            
            // Nếu có lệnh xóa thì gọi hàm delete(), không thì gọi index() để hiện danh sách
            if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
                $adminProductController->delete($_GET['id']); 
            } else {
                $adminProductController->index(); 
            }
            ?>
     <?php elseif ($page == 'add_product'): ?>
            <?php
            // XỬ LÝ THÊM SẢN PHẨM MỚI VÀ LƯU ẢNH
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
                $new_name = mysqli_real_escape_string($db, $_POST['product_name']);
                $new_price = mysqli_real_escape_string($db, $_POST['price']);
                $new_qty = mysqli_real_escape_string($db, $_POST['stock_quantity']);
                $new_cat = mysqli_real_escape_string($db, $_POST['category_id']);
                $new_desc = mysqli_real_escape_string($db, $_POST['description']);
                $new_status = isset($_POST['status']) ? (int)$_POST['status'] : 1; 

                // Logic xử lý upload ảnh
                $image_name = 'default.jpg'; // Mặc định nếu Admin quên chọn ảnh
                if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                    $image_name = time() . '_' . $_FILES['image']['name']; 
                    $target_dir = "../assets/img/"; 
                    $target_file = $target_dir . basename($image_name);
                    
                    // Đẩy ảnh vào thư mục
                    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
                }

                // Chèn dữ liệu vào Database
                mysqli_query($db, "INSERT INTO products (product_name, price, stock_quantity, category_id, description, status, image) 
                                   VALUES ('$new_name', '$new_price', '$new_qty', '$new_cat', '$new_desc', '$new_status', '$image_name')");                
                
                // Thêm xong thì quay về trang danh sách sản phẩm
                header("Location: index.php?page=products");
                exit();
            }
            ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">Sản Phẩm Mới</h2>
                <a href="index.php?page=products" class="btn btn-secondary fw-bold"><i class="fas fa-arrow-left me-1"></i>Quay Lại</a>
            </div>
            
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-8 mb-4">
                        <div class="card shadow-sm border-0 p-4 h-100">
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label fw-bold">Tên Sản Phẩm</label>
                                    <input type="text" name="product_name" class="form-control border-primary" placeholder="Enter product name..." required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Chọn Danh Mục</label>
                                    <select name="category_id" class="form-select border-primary" required>
                                        <option value="" disabled selected>-- Chọn Danh Mục --</option>
                                        <?php
                                        $cat_query = mysqli_query($db, "SELECT * FROM categories");
                                        if ($cat_query) {
                                            while ($c = mysqli_fetch_assoc($cat_query)) {
                                                $c_id = $c['category_id'] ?? $c['id'];
                                                $c_name = $c['category_name'] ?? $c['name'] ?? 'Category ' . $c_id;
                                                echo "<option value='{$c_id}'>{$c_name}</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>                   
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Giá Bán (VNĐ)</label>
                                    <input type="number" name="price" class="form-control border-primary" placeholder="0" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Tồn Kho Ban Đầu</label>
                                    <input type="number" name="stock_quantity" class="form-control border-primary" placeholder="0" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Trạng Thái</label>
                                    <select name="status" class="form-select border-primary" style="cursor: pointer;">
                                        <option value="1" selected>Hiển thị</option>
                                        <option value="0">Ẩn</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-4">
                        <div class="card shadow-sm border-0 p-4 h-100 d-flex flex-column align-items-center justify-content-center">
                            <img id="imagePreviewAdd" src="https://placehold.co/220x220?text=Chưa+Có+Ảnh" alt="Preview" style="width: 220px; height: 220px; object-fit: cover; border: 3px solid #0d6efd; border-radius: 8px; margin-bottom: 20px;">
                            
                            <label for="imageUploadAdd" class="btn btn-outline-primary fw-bold px-4 py-2" style="border-width: 2px; cursor: pointer;">CHỌN ẢNH BÁNH</label>
                            <input type="file" name="image" id="imageUploadAdd" class="d-none" accept="image/*" onchange="document.getElementById('imagePreviewAdd').src = window.URL.createObjectURL(this.files[0])">
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 p-4 mb-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Mô tả Sản Phẩm</label>
                        <textarea name="description" class="form-control border-primary" rows="5" placeholder="Enter details about ingredients, flavor of the bread..."></textarea>
                    </div>
                    
                    <div>
                        <button type="submit" name="add_product" class="btn btn-primary px-4 py-2 fw-bold"><i class="fas fa-plus-circle me-2"></i>Thêm Sản Phẩm Mới</button>
                    </div>
                </div>
            </form>
        <?php elseif ($page == 'product_detail'): ?>
            <?php
            $detail_id = isset($_GET['id']) ? mysqli_real_escape_string($db, $_GET['id']) : 0;
            $prod_info = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM products WHERE product_id = '$detail_id'"));
            
            if(!$prod_info) { echo "<div class='alert alert-danger m-4'>Không tìm thấy thông tin sản phẩm!</div>"; } 
            else {
                $p_name = $prod_info['product_name'] ?? 'N/A';
                $p_price = $prod_info['price'] ?? 0;
                $p_qty = $prod_info['stock_quantity'] ?? 0;
                $p_cat = $prod_info['category_id'] ?? 'Không rõ';
                $p_img = $prod_info['image'] ?? 'default.jpg';
            ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">Product Details #<?php echo $detail_id; ?></h2>
                <a href="index.php?page=products" class="btn btn-secondary"><i class="fas fa-arrow-left"></i>Quay Lại</a>
            </div>
            <div class="card shadow-sm border-0 p-4">
                <div class="row">
                    <div class="col-md-4 text-center mb-4 mb-md-0">
                        <img src='../assets/img/<?php echo $p_img; ?>' class='img-fluid rounded shadow' style='max-height: 350px; object-fit: cover;' onerror="this.onerror=null; this.src='https://placehold.co/350x350?text=No+Image';">
                    </div>
                    <div class="col-md-8">
                        <h3 class="fw-bold text-primary mb-3"><?php echo $p_name; ?></h3>
                        <h4 class="text-danger fw-bold mb-4"><?php echo number_format((float)$p_price, 0, ',', '.'); ?> đ</h4>
                        <table class="table table-bordered align-middle">
                            <tbody>
                                <tr><th class="bg-light" style="width: 30%;">Product ID:</th><td class="fw-bold text-secondary">#<?php echo $detail_id; ?></td></tr>
                                <tr><th class="bg-light">Current Stock:</th><td><?php echo ($p_qty > 0) ? "<span class='badge bg-success px-3 py-2'>{$p_qty} items</span>" : "<span class='badge bg-danger px-3 py-2'>Hết Hàng</span>"; ?></td></tr>
                                <tr><th class="bg-light">Category ID:</th><td><?php echo $p_cat; ?></td></tr>
                            </tbody>
                        </table>
                        <div class="mt-4">
                            <a href='index.php?page=edit_product&id=<?php echo $detail_id; ?>' class='btn btn-primary me-2 fw-bold'><i class='fas fa-edit me-2'></i>Sửa</a>
                            <a href='index.php?page=products&action=delete_product&id=<?php echo $detail_id; ?>' class='btn btn-outline-danger fw-bold' onclick="confirmAction(event, this.href, 'Delete this product?');"><i class='fas fa-trash me-2'></i>Xóa</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>

     <?php elseif ($page == 'edit_product'): ?>
            <?php
            $edit_id = isset($_GET['id']) ? mysqli_real_escape_string($db, $_GET['id']) : 0;
            
            // XỬ LÝ LƯU SẢN PHẨM VÀ HÌNH ẢNH
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
                $new_name = mysqli_real_escape_string($db, $_POST['product_name']);
                $new_price = mysqli_real_escape_string($db, $_POST['price']);
                $new_qty = mysqli_real_escape_string($db, $_POST['stock_quantity']);
                $new_cat = mysqli_real_escape_string($db, $_POST['category_id']);
                $new_desc = mysqli_real_escape_string($db, $_POST['description']);
                $new_status = isset($_POST['status']) ? (int)$_POST['status'] : 1; 

                // Logic xử lý đổi ảnh
                $update_img_sql = ""; 
                if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                    $image_name = time() . '_' . $_FILES['image']['name']; 
                    $target_dir = "../assets/img/"; 
                    $target_file = $target_dir . basename($image_name);
                    
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        $update_img_sql = ", image = '$image_name'"; 
                    }
                }

                // Cập nhật vào DB
                mysqli_query($db, "UPDATE products SET product_name = '$new_name', price = '$new_price', stock_quantity = '$new_qty', category_id = '$new_cat', description = '$new_desc', status = '$new_status' $update_img_sql WHERE product_id = '$edit_id'");                
                
                header("Location: index.php?page=products");
                exit();
            }

            $prod_info = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM products WHERE product_id = '$edit_id'"));
            if(!$prod_info) { echo "<div class='alert alert-danger m-4'>Không tìm thấy sản phẩm!</div>"; } 
            else {
            ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">Edit Product #<?php echo $edit_id; ?></h2>
                <a href="index.php?page=products" class="btn btn-secondary fw-bold"><i class="fas fa-arrow-left me-1"></i>Quay Lại</a>
            </div>
            
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-8 mb-4">
                        <div class="card shadow-sm border-0 p-4 h-100">
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label fw-bold">Tên Sản Phẩm</label>
                                    <input type="text" name="product_name" class="form-control border-warning" value="<?php echo $prod_info['product_name'] ?? ''; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Chọn Danh Mục</label>
                                    <select name="category_id" class="form-select border-warning" required>
                                        <?php
                                        $cat_query = mysqli_query($db, "SELECT * FROM categories");
                                        if ($cat_query) {
                                            while ($c = mysqli_fetch_assoc($cat_query)) {
                                                $c_id = $c['category_id'] ?? $c['id'];
                                                $c_name = $c['category_name'] ?? $c['name'] ?? 'Category ' . $c_id;
                                                $selected = ($c_id == ($prod_info['category_id'] ?? '')) ? 'selected' : '';
                                                echo "<option value='{$c_id}' {$selected}>{$c_name}</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>                   
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Giá Bán (VNĐ)</label>
                                    <input type="number" name="price" class="form-control border-warning" value="<?php echo $prod_info['price'] ?? 0; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Stock Quantity</label>
                                    <input type="number" name="stock_quantity" class="form-control border-warning" value="<?php echo $prod_info['stock_quantity'] ?? 0; ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Trạng Thái</label>
                                    <?php $p_status = isset($prod_info['status']) ? (int)$prod_info['status'] : 1; ?>
                                    <select name="status" class="form-select border-warning" style="cursor: pointer;">
                                        <option value="1" <?php echo ($p_status === 1) ? 'selected' : ''; ?>>Hiển thị</option>
                                        <option value="0" <?php echo ($p_status === 0) ? 'selected' : ''; ?>>Ẩn</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-4">
                        <div class="card shadow-sm border-0 p-4 h-100 d-flex flex-column align-items-center justify-content-center">
                            <?php $current_img = $prod_info['image'] ?? 'default.jpg'; ?>
                            
                            <img id="imagePreview" src="../assets/img/<?php echo $current_img; ?>" alt="Product Image" style="width: 220px; height: 220px; object-fit: cover; border: 3px solid #dc3545; border-radius: 8px; margin-bottom: 20px;" onerror="this.onerror=null; this.src='https://placehold.co/220x220?text=No+Image';">
                            
                            <label for="imageUpload" class="btn btn-outline-danger fw-bold px-4 py-2" style="border-width: 2px; cursor: pointer;">
                                EDIT IMAGE
                            </label>
                            <input type="file" name="image" id="imageUpload" class="d-none" accept="image/*" onchange="document.getElementById('imagePreview').src = window.URL.createObjectURL(this.files[0])">
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 p-4 mb-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Mô tả Sản Phẩm</label>
                        <textarea name="description" class="form-control border-warning" rows="5"><?php echo $prod_info['description'] ?? ''; ?></textarea>
                    </div>
                    
                    <div>
                        <button type="submit" name="update_product" class="btn btn-success px-4 py-2 fw-bold"><i class="fas fa-save me-2"></i>Lưu Thay Đổi</button>
                    </div>
                </div>
            </form>
            <?php } ?>

     <?php elseif ($page == 'orders' || $page == 'order_detail' || $page == 'print_invoice'): ?>
            <?php
            require_once '../app/controllers/AdminOrderController.php';
            $adminOrderController = new AdminOrderController($db);
            
            // Nếu là trang chi tiết thì gọi detail(), không thì vào index() hoặc xử lý nút Duyệt
            if ($page == 'order_detail') {
                $adminOrderController->detail();
            } elseif ($page == 'print_invoice') {
                $adminOrderController->printInvoice();
            } else {
                if (isset($_GET['action'])) {
                    if ($_GET['action'] == 'approve') $adminOrderController->approve();
                    elseif ($_GET['action'] == 'complete') $adminOrderController->complete();
                } else {
                    $adminOrderController->index(); 
                }
            }
            ?>
<?php elseif ($page == 'categories'): ?>
            <?php
            $search = isset($_GET['search']) ? mysqli_real_escape_string($db, trim($_GET['search'])) : '';
            $cats_query = null;
            try {
                $where_clause = $search != '' ? " WHERE category_name LIKE '%$search%'" : "";
                $cats_query = mysqli_query($db, "SELECT * FROM categories $where_clause ORDER BY category_id DESC");
            } catch (Exception $e) {
                try {
                    $where_clause = $search != '' ? " WHERE name LIKE '%$search%'" : "";
                    $cats_query = mysqli_query($db, "SELECT * FROM categories $where_clause ORDER BY id DESC");
                } catch (Exception $e2) {}
            }
            include 'views/categories.php';
            ?>
<?php elseif ($page == 'add_category'): ?>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_add_category'])) {
                $cat_name = mysqli_real_escape_string($db, trim($_POST['category_name']));
                $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;

                if (!empty($cat_name)) {
                    
           
                    $check_exist = mysqli_query($db, "SELECT * FROM categories WHERE category_name = '$cat_name'");
                    
                    if ($check_exist && mysqli_num_rows($check_exist) > 0) {
  
                        echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Lỗi!\", text: \"Danh mục '$cat_name' đã tồn tại trong hệ thống.\", confirmButtonColor: \"#d33\", icon: \"error\"}).then((result) => { window.history.back(); });});</script>";
                    } else {
                        $inserted = mysqli_query($db, "INSERT INTO categories (category_name, status) VALUES ('$cat_name', '$status')");
                        
                        if ($inserted) {
                            echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thành công\", text: \"Category added successfully!\", confirmButtonColor: \"#c4a16b\", icon: \"success\"}).then((result) => { window.location.href = 'index.php?page=categories'; });});</script>";
                            exit();
                        } else {
                            echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Lỗi hệ thống\", text: \"Database Error: Cannot add category.\", confirmButtonColor: \"#d33\", icon: \"error\"}).then((result) => {  });});</script>";
                        }
                    }
                }
            }
            include 'views/add_category.php';
            ?>

<?php elseif ($page == 'edit_category'): ?>
            <?php
            $edit_id = isset($_GET['id']) ? mysqli_real_escape_string($db, $_GET['id']) : 0;
        
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_update_category'])) {
                $cat_name = mysqli_real_escape_string($db, trim($_POST['category_name']));
                $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;

                try {
                    // Thử update cột category_name
                    $updated = mysqli_query($db, "UPDATE categories SET category_name = '$cat_name', status = '$status' WHERE category_id = '$edit_id'");
                    // Nếu bảng dùng tên cột là name và id thì thử lại
                    if (!$updated) {
                        $updated = mysqli_query($db, "UPDATE categories SET name = '$cat_name', status = '$status' WHERE id = '$edit_id'");
                    }
                    
                    // Lưu thành công thì ép chuyển trang
                    header("Location: index.php?page=categories");
                    exit();
                    
                } catch (Exception $e) {
                    // Bắt lỗi sập web (Ví dụ quên chưa thêm cột status)
                    $error_msg = addslashes($e->getMessage());
                    echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Lỗi Database: \" . $error_msg . \"\\n\\nÔng nhớ chạy lệnh ALTER TABLE categories trong phpMyAdmin nhé!\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.history.back(); });});</script>";
                    exit();
                }
            }
            
            // Lấy thông tin danh mục
            $cat_info = null;
            $cat_res = @mysqli_query($db, "SELECT * FROM categories WHERE category_id = '$edit_id'");
            if (!$cat_res || mysqli_num_rows($cat_res) == 0) {
                $cat_res = @mysqli_query($db, "SELECT * FROM categories WHERE id = '$edit_id'");
            }
            if ($cat_res) {
                $cat_info = mysqli_fetch_assoc($cat_res);
            }

            if (!$cat_info) {
                echo "<div class='alert alert-danger m-4'>Category not found!</div>";
            } else {
                include 'views/edit_category.php';
            }
            ?>
<?php elseif ($page == 'accounts'): ?>
    <?php
    require_once '../app/controllers/AdminAccountController.php';
    $adminAccountController = new AdminAccountController($db);
    $adminAccountController->index();
    ?>
<?php elseif ($page == 'add_account'): ?>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy dữ liệu từ form
            $full_name = mysqli_real_escape_string($db, trim($_POST['full_name']));
            $username = mysqli_real_escape_string($db, trim($_POST['username']));
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Mã hóa mật khẩu
            $email = mysqli_real_escape_string($db, trim($_POST['email']));
            $phone = mysqli_real_escape_string($db, trim($_POST['phone_number']));
            $address = mysqli_real_escape_string($db, trim($_POST['address']));
            $role = mysqli_real_escape_string($db, $_POST['role']);

            $check_email = mysqli_query($db, "SELECT * FROM customers WHERE email = '$email'");
            if (mysqli_num_rows($check_email) > 0) {
                echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Lỗi: Email này đã được sử dụng! Vui lòng chọn email khác.\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.history.back(); });});</script>";
                exit();
            }

            // 2. Kiểm tra Username đã tồn tại chưa (Rất quan trọng)
            $check_user = mysqli_query($db, "SELECT * FROM accounts WHERE username = '$username'");
            if (mysqli_num_rows($check_user) > 0) {
                echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Lỗi: Tên đăng nhập này đã có người dùng! Vui lòng chọn tên khác.\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.history.back(); });});</script>";
                exit();
            }

            $sql_customer = "INSERT INTO customers (full_name, email, phone_number, address) 
                             VALUES ('$full_name', '$email', '$phone', '$address')";
            
            if (mysqli_query($db, $sql_customer)) {
                // Lấy ID của customer vừa tạo
                $new_customer_id = mysqli_insert_id($db);
                
                // Thêm vào bảng accounts
                $sql_account = "INSERT INTO accounts (customer_id, username, password, role) 
                                VALUES ('$new_customer_id', '$username', '$password', '$role')";
                mysqli_query($db, $sql_account);
                
                echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Thêm tài khoản thành công!\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.location.href = 'index.php?page=accounts'; });});</script>";
                exit();
            } else {
                echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Lỗi: Không thể thêm dữ liệu. Vui lòng kiểm tra lại!\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.history.back(); });});</script>";
                exit();
            }
        }
        include 'views/add_account.php';
        ?>

  <?php elseif ($page == 'edit_account'): ?>
        <?php
        if (!isset($_GET['id'])) {
            header("Location: index.php?page=accounts");
            exit();
        }
        $edit_id = mysqli_real_escape_string($db, $_GET['id']);
        
        // KHI BẤM NÚT CẬP NHẬT
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $full_name = mysqli_real_escape_string($db, trim($_POST['full_name']));
            $username = mysqli_real_escape_string($db, trim($_POST['username']));
            $email = mysqli_real_escape_string($db, trim($_POST['email']));
            $phone = mysqli_real_escape_string($db, trim($_POST['phone_number']));
            $address = mysqli_real_escape_string($db, trim($_POST['address']));
            $role = mysqli_real_escape_string($db, $_POST['role']);

            // 1. Kiểm tra trùng Email (Nhưng BỎ QUA email của chính tài khoản này)
            $check_email = mysqli_query($db, "SELECT * FROM customers WHERE email = '$email' AND customer_id != '$edit_id'");
            if (mysqli_num_rows($check_email) > 0) {
                echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Lỗi: Email này đã được người khác sử dụng!\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.history.back(); });});</script>";
                exit();
            }

            // 2. Kiểm tra trùng Username (BỎ QUA username của chính tài khoản này)
            $check_user = mysqli_query($db, "SELECT * FROM accounts WHERE username = '$username' AND customer_id != '$edit_id'");
            if (mysqli_num_rows($check_user) > 0) {
                echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Lỗi: Tên đăng nhập này đã tồn tại!\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.history.back(); });});</script>";
                exit();
            }

            // 3. Cập nhật bảng accounts
            mysqli_query($db, "UPDATE accounts SET username = '$username', role = '$role' WHERE customer_id = '$edit_id'");
            
            // 4. Cập nhật bảng customers
            mysqli_query($db, "UPDATE customers SET full_name = '$full_name', email = '$email', phone_number = '$phone', address = '$address' WHERE customer_id = '$edit_id'");
            
            // 5. Nếu Admin có gõ mật khẩu mới thì tiến hành cập nhật mật khẩu
            if (!empty($_POST['password'])) {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                mysqli_query($db, "UPDATE accounts SET password = '$password' WHERE customer_id = '$edit_id'");
            }
            
            echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Cập nhật tài khoản thành công!\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.location.href = 'index.php?page=accounts'; });});</script>";
            exit();
        }
        
        // LẤY DỮ LIỆU CŨ ĐỂ ĐIỀN VÀO FORM (Dùng JOIN để lấy toàn bộ từ 2 bảng)
        $sql = "SELECT a.username, a.role, c.* FROM accounts a LEFT JOIN customers c ON a.customer_id = c.customer_id WHERE a.customer_id = '$edit_id'";
        $result = mysqli_query($db, $sql);
        $account = mysqli_fetch_assoc($result);
        
        include 'views/edit_account.php';
        ?>
    <?php elseif ($page == 'customers'): ?>
        <?php
        // Đảm bảo đường dẫn này đúng với cấu trúc thư mục của ông
        require_once '../app/controllers/AdminCustomerController.php'; 
        $adminCustomerController = new AdminCustomerController($db);
        $adminCustomerController->index();
        ?>
<?php elseif ($page == 'customers'): ?>
    <?php
    // Đảm bảo đường dẫn này đúng với cấu trúc thư mục của ông
    require_once '../app/controllers/AdminCustomerController.php'; 
    $adminCustomerController = new AdminCustomerController($db);
    $adminCustomerController->index();
    ?>
      <?php elseif ($page == 'reviews' || $page == 'review_detail'): ?>
            <?php
            require_once '../app/controllers/AdminReviewController.php';
            $adminReviewController = new AdminReviewController($db);
            
            if ($page == 'review_detail' && isset($_GET['id'])) {
                $adminReviewController->detail($_GET['id']);
            } else {
                $adminReviewController->index();
            }
            ?>

            <?php elseif ($page == 'contacts'): ?>
    <?php
    require_once '../app/controllers/AdminContactController.php';
    $adminContactController = new AdminContactController($db);
    $adminContactController->index();
    ?>
        <?php endif; ?>
    </div>
<script>
function cancelOrder(orderId) {
    console.log("Nút Hủy được click cho đơn hàng:", orderId);
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Hủy đơn hàng #' + orderId,
            input: 'textarea',
            inputLabel: 'Lý do hủy đơn hàng',
            inputPlaceholder: 'Nhập lý do hủy...',
            inputAttributes: {
                'aria-label': 'Nhập lý do hủy'
            },
            showCancelButton: true,
            confirmButtonText: 'Xác nhận hủy',
            cancelButtonText: 'Đóng',
            confirmButtonColor: '#d33',
            inputValidator: (value) => {
                if (!value || value.trim() === '') {
                    return 'Vui lòng nhập lý do hủy đơn hàng!'
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const reason = encodeURIComponent(result.value.trim());
                window.location.href = `index.php?page=orders&action=cancel&id=${orderId}&reason=${reason}`;
            }
        });
    } else {
        console.warn("SweetAlert2 không load được, dùng prompt mặc định.");
        var reason = prompt('Nhập lý do hủy đơn hàng #' + orderId + ':');
        if (reason !== null) {
            if (reason.trim() === '') {
                alert('Vui lòng nhập lý do hủy đơn hàng!');
            } else {
                window.location.href = `index.php?page=orders&action=cancel&id=${orderId}&reason=` + encodeURIComponent(reason.trim());
            }
        }
    }
}
</script>
</body>
</html>