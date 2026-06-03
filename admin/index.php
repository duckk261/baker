<?php
ob_start();
session_start();

// 1. KIỂM TRA QUYỀN ADMIN
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
    echo "<script>alert('Access Denied! Bạn không có quyền truy cập khu vực này.'); window.location.href='../index.php';</script>";
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
        echo "<script>alert('Duyệt thành công! Đơn hàng đang được vận chuyển.'); window.location.href='index.php?page=" . $back_page . "';</script>";
        exit();
    }
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $del_id = mysqli_real_escape_string($db, $_GET['id']);
    // Xóa tài khoản
    mysqli_query($db, "DELETE FROM accounts WHERE customer_id = '$del_id'");
    echo "<script>alert('Đã xóa tài khoản!'); window.location.href='index.php?page=accounts';</script>";
    exit();
}
    elseif ($_GET['action'] == 'complete' && isset($_GET['id'])) {
        $complete_id = mysqli_real_escape_string($db, $_GET['id']);
        mysqli_query($db, "UPDATE orders SET status = 'Hoan_tat' WHERE order_id = '$complete_id'");
        $back_page = isset($_GET['back']) ? $_GET['back'] : 'orders';
        echo "<script>alert('Đơn hàng đã giao thành công và hoàn tất!'); window.location.href='index.php?page=" . $back_page . "';</script>";
        exit();
    }
    elseif ($_GET['action'] == 'delete_product' && isset($_GET['id'])) {
        $del_id = mysqli_real_escape_string($db, $_GET['id']);
        mysqli_query($db, "DELETE FROM cart WHERE product_id = '$del_id'");
        mysqli_query($db, "DELETE FROM orderdetails WHERE product_id = '$del_id'");
        if (mysqli_query($db, "DELETE FROM products WHERE product_id = '$del_id'")) {
            echo "<script>alert('Đã xóa sản phẩm!'); window.location.href='index.php?page=products';</script>";
        } else {
            echo "<script>alert('Lỗi hệ thống: Không thể xóa sản phẩm này.'); window.location.href='index.php?page=products';</script>";
        }
        exit();
    }
    elseif ($_GET['action'] == 'delete_category' && isset($_GET['id'])) {
        $del_cat_id = mysqli_real_escape_string($db, $_GET['id']);
        
        // Chuyển các sản phẩm thuộc danh mục này về danh mục mặc định hoặc xóa (Tùy logic đồ án, ở đây tạm thời cho phép xóa danh mục)
        $deleted = @mysqli_query($db, "DELETE FROM categories WHERE category_id = '$del_cat_id'");
        if (!$deleted) {
            $deleted = @mysqli_query($db, "DELETE FROM categories WHERE id = '$del_cat_id'");
        }

        if ($deleted) {
            echo "<script>alert('Category deleted successfully!'); window.location.href='index.php?page=categories';</script>";
        } else {
            echo "<script>alert('Error: Cannot delete this category.'); window.location.href='index.php?page=categories';</script>";
        }
        exit();
    }
 // ================= XỬ LÝ NÚT XÓA/ẨN TRONG TRANG CHI TIẾT REVIEW =================
    elseif ($_GET['action'] == 'delete_review' && isset($_GET['id'])) {
        $del_id = mysqli_real_escape_string($db, $_GET['id']);
        mysqli_query($db, "DELETE FROM reviews WHERE review_id = '$del_id'");
        
        // Dùng REFERER để tự động quay lại đúng trang chi tiết sản phẩm hiện tại
        $ref = $_SERVER['HTTP_REFERER'] ?? 'index.php?page=reviews';
        echo "<script>alert('Đã xóa bình luận này khỏi hệ thống!'); window.location.href='$ref';</script>";
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
    <title>Baker Store - Admin Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Segoe+UI:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
</head>
<body>

    <div class="sidebar shadow">
        <div class="brand"><i class="fas fa-bread-slice me-2"></i> Baker Admin</div>
        <a href="index.php?page=dashboard" class="<?php echo ($page == 'dashboard') ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
        <a href="index.php?page=products" class="<?php echo ($page == 'products' || $page == 'product_detail' || $page == 'edit_product' || $page == 'add_product') ? 'active' : ''; ?>"><i class="fas fa-box-open me-2"></i> Products</a>
        <a href="index.php?page=categories" class="<?php echo ($page == 'categories') ? 'active' : ''; ?>"><i class="fas fa-list me-2"></i> Categories</a>
        <a href="index.php?page=orders" class="<?php echo ($page == 'orders' || $page == 'order_detail') ? 'active' : ''; ?>"><i class="fas fa-shopping-cart me-2"></i> Orders</a>
        <a href="index.php?page=customers" class="<?php echo ($page == 'customers') ? 'active' : ''; ?>"><i class="fas fa-users me-2"></i> Customers</a>
        <a href="index.php?page=reviews" class="<?php echo ($page == 'reviews' || $page == 'review_detail') ? 'active' : ''; ?>"><i class="fas fa-star me-2"></i> Reviews</a>
        <a href="index.php?page=contacts" class="<?php echo ($page == 'contacts') ? 'active' : ''; ?>"><i class="fas fa-comments me-2"></i> Contacts</a>
        <a href="index.php?page=accounts" class="<?php echo ($page == 'accounts') ? 'active' : ''; ?>"><i class="fas fa-user-tie me-2"></i> Accounts</a>
        <hr style="border-color: #666; margin: 20px;">
        <a href="../index.php" class="text-warning"><i class="fas fa-store me-2"></i> Back to Store</a>
        <a href="../index.php?page=logout" class="text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
    </div>
    <div class="main-content">
        
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
            // Xử lý khi bấm nút Thêm
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
                $new_name = mysqli_real_escape_string($db, $_POST['product_name']);
                $new_cat = mysqli_real_escape_string($db, $_POST['category_id']);
                $new_price = mysqli_real_escape_string($db, $_POST['price']);
                $new_qty = mysqli_real_escape_string($db, $_POST['stock_quantity']);
                $image_name = 'default.jpg';
                $new_desc = mysqli_real_escape_string($db, $_POST['description']); // Nhận mô tả
                $new_status = isset($_POST['status']) ? (int)$_POST['status'] : 1; // Hứng trạng thái
                if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == 0) {
                    $upload_dir = '../assets/img/';
                    $file_name = basename($_FILES['image_file']['name']);
                    $image_name = time() . '_' . $file_name;
                    $target_file = $upload_dir . $image_name;
                    if (!move_uploaded_file($_FILES['image_file']['tmp_name'], $target_file)) {
                        echo "<script>alert('Không thể tải ảnh lên. Dùng ảnh mặc định!');</script>";
                        $image_name = 'default.jpg';
                    }
                }
                
    $insert_query = "INSERT INTO products (product_name, category_id, price, stock_quantity, image, description, status) VALUES ('$new_name', '$new_cat', '$new_price', '$new_qty', '$image_name', '$new_desc', $new_status)";                if (mysqli_query($db, $insert_query)) {
                    echo "<script>alert('Thêm sản phẩm thành công!'); window.location.href='index.php?page=products';</script>";
                    exit();
                } else {
                    echo "<script>alert('Lỗi Database!');</script>";
                }
            }

            // TÍNH TOÁN ID TIẾP THEO SẼ ĐƯỢC THÊM
            $get_max_id = mysqli_query($db, "SELECT MAX(product_id) AS max_id FROM products");
            $row_id = mysqli_fetch_assoc($get_max_id);
            $next_id = ($row_id['max_id'] ?? 0) + 1;
            ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">Thêm Sản Phẩm Mới</h2>
                <a href="index.php?page=products" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
            </div>
            <div class="card shadow-sm border-0 p-4" style="max-width: 800px;">
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label class="form-label fw-bold text-muted">ID (Tự động)</label>
                            <input type="text" class="form-control bg-light text-center fw-bold text-danger" value="#<?php echo $next_id; ?>" readonly disabled>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tên sản phẩm</label>
                            <input type="text" name="product_name" class="form-control border-primary" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Chọn danh mục</label>
                            <select name="category_id" class="form-select border-primary" required>
                                <option value="">-- Click để chọn --</option>
                                <?php
                                $cat_query = mysqli_query($db, "SELECT * FROM categories");
                                if ($cat_query) {
                                    while ($c = mysqli_fetch_assoc($cat_query)) {
                                        $c_id = $c['category_id'] ?? $c['id'];
                                        $c_name = $c['category_name'] ?? $c['name'] ?? 'Danh mục ' . $c_id;
                                        echo "<option value='{$c_id}'>{$c_name}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Giá bán (VNĐ)</label>
                            <input type="number" name="price" class="form-control border-primary" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Số lượng (Tồn kho)</label>
                            <input type="number" name="stock_quantity" class="form-control border-primary" required>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Trạng thái hiển thị</label>
                            <select name="status" class="form-select border-primary" style="cursor: pointer;">
                                <option value="1">Visible</option>
                                <option value="0">Hidden</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4 p-3 bg-light rounded border border-primary">
                        <label class="form-label fw-bold text-primary"><i class="fas fa-cloud-upload-alt me-2"></i>Tải ảnh sản phẩm lên</label>
                        <input type="file" name="image_file" class="form-control" accept="image/*">
                        <small class="text-muted mt-2 d-block">Để trống sẽ dùng ảnh mặc định.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mô tả sản phẩm</label>
                        <textarea name="description" class="form-control border-primary" rows="4" placeholder="Nhập mô tả chi tiết cho bánh..."></textarea>
                    </div>
                    <button type="submit" name="add_product" class="btn btn-primary px-4 fw-bold fs-5"><i class="fas fa-plus-circle me-2"></i>Xác nhận Thêm Mới</button>
                </form>
            </div>
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
                <h2 class="fw-bold mb-0">Chi tiết Sản phẩm #<?php echo $detail_id; ?></h2>
                <a href="index.php?page=products" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
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
                                <tr><th class="bg-light" style="width: 30%;">Mã sản phẩm:</th><td class="fw-bold text-secondary">#<?php echo $detail_id; ?></td></tr>
                                <tr><th class="bg-light">Tồn kho hiện tại:</th><td><?php echo ($p_qty > 0) ? "<span class='badge bg-success px-3 py-2'>{$p_qty} cái</span>" : "<span class='badge bg-danger px-3 py-2'>Hết hàng</span>"; ?></td></tr>
                                <tr><th class="bg-light">Mã Danh mục:</th><td><?php echo $p_cat; ?></td></tr>
                            </tbody>
                        </table>
                        <div class="mt-4">
                            <a href='index.php?page=edit_product&id=<?php echo $detail_id; ?>' class='btn btn-primary me-2 fw-bold'><i class='fas fa-edit me-2'></i>Sửa</a>
                            <a href='index.php?page=products&action=delete_product&id=<?php echo $detail_id; ?>' class='btn btn-outline-danger fw-bold' onclick="return confirm('Xóa sản phẩm này?');"><i class='fas fa-trash me-2'></i>Xóa</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>

       <?php elseif ($page == 'edit_product'): ?>
            <?php
            $edit_id = isset($_GET['id']) ? mysqli_real_escape_string($db, $_GET['id']) : 0;
            
            // XỬ LÝ LƯU SẢN PHẨM 
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
                $new_name = mysqli_real_escape_string($db, $_POST['product_name']);
                $new_price = mysqli_real_escape_string($db, $_POST['price']);
                $new_qty = mysqli_real_escape_string($db, $_POST['stock_quantity']);
                $new_cat = mysqli_real_escape_string($db, $_POST['category_id']);
                $new_desc = mysqli_real_escape_string($db, $_POST['description']);
                $new_status = isset($_POST['status']) ? (int)$_POST['status'] : 1; 

                mysqli_query($db, "UPDATE products SET product_name = '$new_name', price = '$new_price', stock_quantity = '$new_qty', category_id = '$new_cat', description = '$new_desc', status = '$new_status' WHERE product_id = '$edit_id'");                
                
                header("Location: index.php?page=products");
                exit();
            }
            $prod_info = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM products WHERE product_id = '$edit_id'"));
            if(!$prod_info) { echo "<div class='alert alert-danger m-4'>Không tìm thấy sản phẩm!</div>"; } 
            else {
            ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">Sửa Sản Phẩm #<?php echo $edit_id; ?></h2>
                <a href="index.php?page=products" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
            </div>
            <div class="card shadow-sm border-0 p-4" style="max-width: 800px;">
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-8 mb-3"><label class="form-label fw-bold">Tên sản phẩm</label><input type="text" name="product_name" class="form-control border-primary" value="<?php echo $prod_info['product_name'] ?? ''; ?>" required></div>
<div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Chọn danh mục</label>
                            <select name="category_id" class="form-select border-primary" required>
                                <?php
                                $cat_query = mysqli_query($db, "SELECT * FROM categories");
                                if ($cat_query) {
                                    while ($c = mysqli_fetch_assoc($cat_query)) {
                                        $c_id = $c['category_id'] ?? $c['id'];
                                        $c_name = $c['category_name'] ?? $c['name'] ?? 'Danh mục ' . $c_id;
                                        
                                        // Kiểm tra xem danh mục nào là của sản phẩm này thì chọn sẵn (selected)
                                        $selected = ($c_id == ($prod_info['category_id'] ?? '')) ? 'selected' : '';
                                        echo "<option value='{$c_id}' {$selected}>{$c_name}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>                   
                     </div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label fw-bold">Giá bán (VNĐ)</label><input type="number" name="price" class="form-control border-primary" value="<?php echo $prod_info['price'] ?? 0; ?>" required></div>
                        <div class="col-md-6 mb-4"><label class="form-label fw-bold">Tồn kho</label><input type="number" name="stock_quantity" class="form-control border-primary" value="<?php echo $prod_info['stock_quantity'] ?? 0; ?>" required></div>
                    </div>
                  <div class="col-md-4 mb-4">
                            <label class="form-label fw-bold">Trạng thái (Status)</label>
                            <?php 
                            $p_status = isset($prod_info['status']) ? (int)$prod_info['status'] : 1; 
                            ?>
                            <select name="status" class="form-select border-primary" style="cursor: pointer;">
                                <option value="1" <?php echo ($p_status === 1) ? 'selected' : ''; ?>>Visible</option>
                                <option value="0" <?php echo ($p_status === 0) ? 'selected' : ''; ?>>Hidden</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Mô tả sản phẩm</label>
                        <textarea name="description" class="form-control border-primary" rows="4"><?php echo $prod_info['description'] ?? ''; ?></textarea>
                    </div>
                    <button type="submit" name="update_product" class="btn btn-success px-4 fw-bold"><i class="fas fa-save me-2"></i>Lưu thay đổi</button>
                </form>
            </div>
            <?php } ?>

     <?php elseif ($page == 'orders' || $page == 'order_detail'): ?>
            <?php
            require_once '../app/controllers/AdminOrderController.php';
            $adminOrderController = new AdminOrderController($db);
            
            // Nếu là trang chi tiết thì gọi detail(), không thì vào index() hoặc xử lý nút Duyệt
            if ($page == 'order_detail') {
                $adminOrderController->detail();
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
            // Comment PHP chuẩn phải nằm ở TRONG thẻ <?php
            // ================= THÊM DANH MỤC MỚI =================
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_add_category'])) {
                $cat_name = mysqli_real_escape_string($db, trim($_POST['category_name']));
                $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;

                if (!empty($cat_name)) {
                    $inserted = @mysqli_query($db, "INSERT INTO categories (category_name, status) VALUES ('$cat_name', '$status')");
                    if (!$inserted) {
                        $inserted = @mysqli_query($db, "INSERT INTO categories (name, status) VALUES ('$cat_name', '$status')");
                    }
                    if ($inserted) {
                        echo "<script>alert('Category added successfully!'); window.location.href='index.php?page=categories';</script>";
                        exit();
                    } else {
                        echo "<script>alert('Database Error: Cannot add category.');</script>";
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
                    echo "<script>alert('Lỗi Database: " . $error_msg . "\\n\\nÔng nhớ chạy lệnh ALTER TABLE categories trong phpMyAdmin nhé!'); window.history.back();</script>";
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
                echo "<script>alert('Lỗi: Email này đã được sử dụng! Vui lòng chọn email khác.'); window.history.back();</script>";
                exit();
            }

            // 2. Kiểm tra Username đã tồn tại chưa (Rất quan trọng)
            $check_user = mysqli_query($db, "SELECT * FROM accounts WHERE username = '$username'");
            if (mysqli_num_rows($check_user) > 0) {
                echo "<script>alert('Lỗi: Tên đăng nhập này đã có người dùng! Vui lòng chọn tên khác.'); window.history.back();</script>";
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
                
                echo "<script>alert('Thêm tài khoản thành công!'); window.location.href='index.php?page=accounts';</script>";
                exit();
            } else {
                echo "<script>alert('Lỗi: Không thể thêm dữ liệu. Vui lòng kiểm tra lại!'); window.history.back();</script>";
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
                echo "<script>alert('Lỗi: Email này đã được người khác sử dụng!'); window.history.back();</script>";
                exit();
            }

            // 2. Kiểm tra trùng Username (BỎ QUA username của chính tài khoản này)
            $check_user = mysqli_query($db, "SELECT * FROM accounts WHERE username = '$username' AND customer_id != '$edit_id'");
            if (mysqli_num_rows($check_user) > 0) {
                echo "<script>alert('Lỗi: Tên đăng nhập này đã tồn tại!'); window.history.back();</script>";
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
            
            echo "<script>alert('Cập nhật tài khoản thành công!'); window.location.href='index.php?page=accounts';</script>";
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
</body>
</html>