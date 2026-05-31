<?php
if(!$order_info) { echo "<div class='alert alert-danger m-4'>Không tìm thấy đơn hàng!</div>"; } 
else {
    $status = $order_info['status'] ?? 'Pending';
    $status_lower = strtolower($status);
    if ($status_lower == 'hoan_tat') { $badge_class = 'bg-success'; } elseif ($status_lower == 'da_thanh_toan') { $badge_class = 'bg-primary'; } elseif ($status_lower == 'dang_giao') { $badge_class = 'bg-info text-dark'; } elseif ($status_lower == 'cho_duyet') { $badge_class = 'bg-warning text-dark'; } else { $badge_class = 'bg-secondary'; }
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">Chi tiết Đơn hàng #<?php echo $order_info['order_id']; ?></h2>
    <a href="index.php?page=orders" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-dark text-white fw-bold"><i class="fas fa-user me-2"></i>Thông tin Khách hàng</div>
            <div class="card-body">
                <p><strong>Họ tên:</strong> <?php echo $order_info['full_name'] ?? 'Khách lẻ'; ?></p>
                <p><strong>Điện thoại:</strong> <?php echo $order_info['phone_number'] ?? 'N/A'; ?></p>
                <p><strong>Địa chỉ giao hàng:</strong> <span class="text-primary fw-bold"><?php echo $order_info['address'] ?? 'N/A'; ?></span></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-dark text-white fw-bold"><i class="fas fa-file-invoice me-2"></i>Thông tin Đơn hàng</div>
            <div class="card-body">
                <p><strong>Mã đơn:</strong> #<?php echo $order_info['order_id']; ?></p>
                <p><strong>Ngày đặt:</strong> <?php echo $order_info['order_date'] ?? 'N/A'; ?></p>
                <p><strong>Trạng thái:</strong> <span class="badge <?php echo $badge_class; ?> fs-6"><?php echo $status; ?></span></p>
                <p><strong>Tổng tiền:</strong> <span class="text-danger fw-bold fs-5"><?php echo number_format((float)($order_info['total_amount'] ?? 0), 0, ',', '.'); ?> đ</span></p>
            </div>
        </div>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-header bg-white fw-bold pb-0 border-0 pt-4"><h5><i class="fas fa-box-open text-primary me-2"></i>Sản phẩm trong đơn</h5></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle border">
                <thead class="table-light"><tr><th>Sản phẩm</th><th>Đơn giá</th><th class="text-center">Số lượng</th><th class="text-end">Thành tiền</th></tr></thead>
                <tbody>
                    <?php
                    if ($order_items && mysqli_num_rows($order_items) > 0) {
                        while ($item = mysqli_fetch_assoc($order_items)) {
                            $p_name = $item['product_name'] ?? 'Sản phẩm ID #'.$item['product_id'];
                            $price = $item['price'] ?? 0;
                            $qty = $item['quantity'] ?? 1;
                            echo "<tr><td class='fw-bold text-secondary'>{$p_name}</td><td>" . number_format((float)$price, 0, ',', '.') . " đ</td><td class='text-center fw-bold'>{$qty}</td><td class='text-danger fw-bold text-end'>" . number_format((float)($price * $qty), 0, ',', '.') . " đ</td></tr>";
                        }
                    } else { echo "<tr><td colspan='4' class='text-center py-4 text-muted'>Không có dữ liệu chi tiết.</td></tr>"; }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php } ?>