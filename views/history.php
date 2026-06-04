<?php 
require_once 'app/classes/Database.php';
$db = Database::getInstance();
$acc_id = $_SESSION['account_id'];

if (isset($_GET['action']) && $_GET['action'] == 'cancel' && isset($_GET['id'])) {
    $cancel_id = (int)$_GET['id'];

    $check_sql = "SELECT * FROM orders WHERE order_id = '$cancel_id' AND customer_id = '$acc_id' AND status = 'Cho_duyet' AND payment_method = 'COD'";    $check_res = mysqli_query($db, $check_sql);

    if ($check_res && mysqli_num_rows($check_res) > 0) {
        mysqli_begin_transaction($db);
        
        try {
            $details_sql = "SELECT product_id, quantity FROM orderdetails WHERE order_id = '$cancel_id'";
            $details_res = mysqli_query($db, $details_sql);

            while ($item = mysqli_fetch_assoc($details_res)) {
                $pid = $item['product_id'];
                $qty = $item['quantity'];
                mysqli_query($db, "UPDATE products SET stock_quantity = stock_quantity + $qty WHERE product_id = '$pid'");
            }
            mysqli_query($db, "UPDATE orders SET status = 'Da_huy' WHERE order_id = '$cancel_id'");
            mysqli_commit($db);
            
            echo "<script>alert('Đã hủy đơn hàng!'); window.location.href='index.php?page=history';</script>";
            exit();
        } catch (Exception $e) {
            mysqli_rollback($db);
            echo "<script>alert('Lỗi hệ thống khi hủy đơn!'); window.location.href='index.php?page=history';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Đơn hàng không tồn tại hoặc không thể hủy lúc này!'); window.location.href='index.php?page=history';</script>";
        exit();
    }
}

$orders_query = null;

try {
    $orders_query = mysqli_query($db, "SELECT * FROM orders WHERE customer_id = '$acc_id' ORDER BY order_id DESC");
} catch (Exception $e) {
    try {
        $orders_query = mysqli_query($db, "SELECT * FROM orders WHERE account_id = '$acc_id' ORDER BY order_id DESC");
    } catch (Exception $e2) {
    }
}

include 'header.php'; 
?>

<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width: 500px;">
            <p class="text-primary text-uppercase mb-2">Purchase History</p>
            <h1 class="display-6 mb-4">Lịch Sử Đơn Hàng</h1>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <?php if ($orders_query && mysqli_num_rows($orders_query) > 0): ?>
                    <?php while ($order = mysqli_fetch_assoc($orders_query)): 
                        $o_id = $order['order_id'];
                        $status = $order['status'] ?? 'Pending';
                        $status_lower = strtolower($status);
                        
                        // Set màu trạng thái
                        if ($status_lower == 'hoan_tat') { $badge = 'bg-success'; } 
                        elseif ($status_lower == 'dang_giao') { $badge = 'bg-info text-dark'; } 
                        elseif ($status_lower == 'cho_duyet') { $badge = 'bg-warning text-dark'; } 
                        else { $badge = 'bg-secondary'; }
                    ?>
                       <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                                <h5 class="mb-0 text-white">Đơn hàng #<?php echo $o_id; ?></h5>
                                <div>
                                    <span class="badge <?php echo $badge; ?> fs-6 me-2"><?php echo $status; ?></span>
                                    
                                   <?php if ($status_lower == 'cho_duyet'): ?>
                                        <?php if (($order['payment_method'] ?? 'COD') == 'COD'): ?>
                                            <a href="index.php?page=history&action=cancel&id=<?php echo $o_id; ?>" class="btn btn-sm btn-danger fw-bold shadow-sm" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng #<?php echo $o_id; ?> này không?');">
                                                <i class="fas fa-times-circle me-1"></i>Hủy
                                            </a>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-sm btn-secondary fw-bold shadow-sm" onclick="alert('Đơn hàng này đã được thanh toán qua chuyển khoản. Nếu bạn muốn hủy đơn, vui lòng liên hệ với Admin (Hotline/Zalo) để được hỗ trợ hoàn tiền nhé!');">
                                                <i class="fas fa-headset me-1"></i>Hủy
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <table class="table align-middle">
                                    <thead class="table-light">
                                        <tr><th>Sản phẩm</th><th>Đơn giá</th><th>Số lượng</th><th>Thành tiền</th><th>Thao tác</th></tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $details_query = mysqli_query($db, "SELECT od.*, p.product_name, p.price as p_price FROM orderdetails od LEFT JOIN products p ON od.product_id = p.product_id WHERE od.order_id = '$o_id'");
                                        
                                        while ($item = mysqli_fetch_assoc($details_query)):
                                            $p_id = $item['product_id'];
                                            $p_name = $item['product_name'] ?? 'Sản phẩm';
                                            $item_price = $item['price'] ?? $item['unit_price'] ?? $item['p_price'] ?? 0;
                                            $item_qty = $item['quantity'] ?? 1;
                                            $item_total = $item_price * $item_qty;
                                            
                                            $check_review = mysqli_query($db, "SELECT * FROM reviews WHERE account_id='$acc_id' AND order_id='$o_id' AND product_id='$p_id'");
                                            $is_reviewed = mysqli_num_rows($check_review) > 0;
                                        ?>
                                            <tr>
                                                <td class="fw-bold text-primary"><?php echo $p_name; ?></td>
                                                <td><?php echo number_format($item_price, 0, ',', '.'); ?>đ</td>
                                                <td><?php echo $item_qty; ?></td>
                                                <td class="text-danger fw-bold"><?php echo number_format($item_total, 0, ',', '.'); ?>đ</td>
                                                <td>
                                                    <?php if ($status_lower == 'hoan_tat'): ?>
                                                        <?php if ($is_reviewed): ?>
                                                            <span class="text-success small fw-bold"><i class="fas fa-check-circle me-1"></i>Đã đánh giá</span>
                                                        <?php else: ?>
                                                            <button class="btn btn-sm btn-outline-warning fw-bold" onclick="openReviewModal(<?php echo $p_id; ?>, <?php echo $o_id; ?>, '<?php echo addslashes($p_name); ?>')">
                                                                <i class="fas fa-star me-1"></i>Đánh giá
                                                            </button>
                                                        <?php endif; ?>
                                                    
                                                    <?php elseif ($status_lower == 'da_huy'): ?>
                                                        <span class="text-danger small fw-bold"><i class="fas fa-ban me-1"></i>Đã hủy</span>
                                                    
                                                    <?php else: ?>
                                                        <span class="text-muted small">Chờ nhận hàng</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                                
                                <div class="d-flex justify-content-between align-items-center mt-3 border-top pt-3">
                                    <div>
                                        <p class="mb-0">
                                            <strong style="color: #444;">Phương thức thanh toán:</strong> 
                                            <span class="badge bg-secondary px-2 py-1 rounded-1 fs-6">
                                                <?php echo htmlspecialchars($order['payment_method'] ?? 'COD'); ?>
                                            </span>
                                        </p>
                                    </div>
                                    <div class="text-end">
                                        <h5 class="fw-bold mb-0">Tổng tiền: <span class="text-danger"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ</span></h5>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <h4 class="text-muted">Bạn chưa có đơn hàng nào.</h4>
                        <a href="index.php?page=product" class="btn btn-primary mt-3">Đi mua sắm ngay</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="index.php?action=submit_review" method="POST">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-dark fw-bold"><i class="fas fa-star me-2"></i>Đánh giá sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 class="text-primary fw-bold mb-3" id="reviewProductName">Tên bánh</h6>
                    <input type="hidden" name="product_id" id="review_product_id">
                    <input type="hidden" name="order_id" id="review_order_id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Chất lượng sản phẩm:</label>
                        <select name="rating" class="form-select border-warning" required>
                            <option value="5">⭐⭐⭐⭐⭐ - Tuyệt vời</option>
                            <option value="4">⭐⭐⭐⭐ - Rất tốt</option>
                            <option value="3">⭐⭐⭐ - Bình thường</option>
                            <option value="2">⭐⭐ - Tệ</option>
                            <option value="1">⭐ - Rất tệ</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nhận xét của bạn:</label>
                        <textarea name="comment" class="form-control border-warning" rows="3" placeholder="Chia sẻ cảm nhận của bạn về hương vị bánh nhé..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-warning fw-bold w-100">Gửi Đánh Giá</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openReviewModal(prodId, orderId, prodName) {
    document.getElementById('review_product_id').value = prodId;
    document.getElementById('review_order_id').value = orderId;
    document.getElementById('reviewProductName').innerText = prodName;
    
    // Gọi modal của Bootstrap 5
    var myModal = new bootstrap.Modal(document.getElementById('reviewModal'));
    myModal.show();
}
</script>

<?php include 'footer.php'; ?>