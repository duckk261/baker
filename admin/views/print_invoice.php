<?php
if (!$order_info) {
    echo "<div class='alert alert-danger m-4'>Không tìm thấy đơn hàng!</div>";
} else {
?>
<style>
    @media print {
        /* Hide everything that doesn't belong on the invoice */
        .sidebar, .navbar, .btn, footer, .main-content > header {
            display: none !important;
        }
        /* Reset margins and paddings for print */
        body {
            margin: 0 !important;
            padding: 0 !important;
            background-color: #fff !important;
        }
        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            box-shadow: none !important;
        }
        /* Remove shadows and borders for a cleaner look */
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        /* Make invoice fill the page */
        .invoice-container {
            width: 100%;
            margin: 0 auto;
        }
    }
    
    /* Screen styling for preview */
    .invoice-container {
        max-width: 800px;
        margin: 20px auto;
        background: #fff;
        padding: 30px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        border-radius: 8px;
    }
</style>

<div class="invoice-container">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-2 border-dark">
        <div>
            <h2 class="fw-bold mb-0 text-danger" style="color: #c4a16b !important;"><i class="fas fa-cookie-bite me-2"></i>BAKER STORE</h2>
            <p class="text-muted mb-0">Hương vị ngọt ngào từ trái tim</p>
            <p class="mb-0 mt-2"><small>12 Chùa Bộc, Đống Đa, Hà Nội</small></p>
            <p class="mb-0"><small>Hotline: 0123 456 789</small></p>
        </div>
        <div class="text-end">
            <h1 class="text-uppercase text-secondary mb-1">Hóa Đơn</h1>
            <p class="fw-bold mb-0 fs-5">#<?php echo $order_info['order_id']; ?></p>
            <p class="text-muted mb-0">Ngày: <?php echo date('d/m/Y', strtotime($order_info['order_date'])); ?></p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-sm-6">
            <h6 class="text-muted text-uppercase fw-bold mb-2">Khách Hàng</h6>
            <h5 class="fw-bold mb-1"><?php echo $order_info['full_name'] ?? 'Khách lẻ'; ?></h5>
            <p class="mb-1"><i class="fas fa-phone-alt me-2 text-muted"></i><?php echo $order_info['phone_number'] ?? 'N/A'; ?></p>
            <p class="mb-0"><i class="fas fa-map-marker-alt me-2 text-muted"></i><?php echo $order_info['address'] ?? 'N/A'; ?></p>
        </div>
        <div class="col-sm-6 text-end">
            <h6 class="text-muted text-uppercase fw-bold mb-2">Thanh Toán</h6>
            <p class="mb-1 fw-bold"><?php echo htmlspecialchars($order_info['payment_method'] ?? 'COD'); ?></p>
            <p class="mb-0"><span class="badge border border-dark text-dark px-2 py-1"><?php echo $order_info['status'] ?? 'Pending'; ?></span></p>
        </div>
    </div>

    <div class="table-responsive mb-4">
        <table class="table table-bordered border-dark">
            <thead class="border-dark text-center" style="background-color: #f8f9fa !important; -webkit-print-color-adjust: exact;">
                <tr>
                    <th style="width: 5%">STT</th>
                    <th style="width: 45%" class="text-start">Tên Bánh</th>
                    <th style="width: 15%">Đơn Giá</th>
                    <th style="width: 10%">SL</th>
                    <th style="width: 25%" class="text-end">Thành Tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stt = 1;
                $subtotal = 0;
                if ($order_items && mysqli_num_rows($order_items) > 0) {
                    while ($item = mysqli_fetch_assoc($order_items)) {
                        $p_name = $item['product_name'] ?? 'Sản phẩm ID #'.$item['product_id'];
                        $price = $item['price'] ?? 0;
                        $qty = $item['quantity'] ?? 1;
                        $total = $price * $qty;
                        $subtotal += $total;
                        echo "<tr>
                                <td class='text-center'>{$stt}</td>
                                <td>{$p_name}</td>
                                <td class='text-center'>" . number_format((float)$price, 0, ',', '.') . " đ</td>
                                <td class='text-center fw-bold'>{$qty}</td>
                                <td class='text-end fw-bold'>" . number_format((float)$total, 0, ',', '.') . " đ</td>
                              </tr>";
                        $stt++;
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center text-muted'>Không có dữ liệu chi tiết.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="row justify-content-end">
        <div class="col-6 col-sm-5">
            <table class="table table-borderless table-sm">
                <tr>
                    <td><strong>Tổng cộng:</strong></td>
                    <td class="text-end"><?php echo number_format((float)$subtotal, 0, ',', '.'); ?> đ</td>
                </tr>
                <tr>
                    <td><strong>Phí vận chuyển:</strong></td>
                    <td class="text-end"><?php echo number_format((float)($order_info['shipping_fee'] ?? 0), 0, ',', '.'); ?> đ</td>
                </tr>
                <tr class="border-top border-dark">
                    <td><strong class="fs-5">Thành tiền:</strong></td>
                    <td class="text-end text-danger"><strong class="fs-5"><?php echo number_format((float)($order_info['total_amount'] ?? 0), 0, ',', '.'); ?> đ</strong></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="text-center mt-5 mb-4">
        <p class="fst-italic text-muted">Cảm ơn quý khách đã ủng hộ BAKER STORE!</p>
    </div>

    <div class="text-center mt-4">
        <button class="btn btn-secondary me-2 px-4" onclick="window.close()"><i class="fas fa-times me-2"></i>Đóng</button>
        <button class="btn btn-primary px-4" onclick="window.print()" style="background-color: #c4a16b; border-color: #c4a16b;"><i class="fas fa-print me-2"></i>In Hóa Đơn</button>
    </div>
</div>

<script>
    window.onload = function() {
        setTimeout(function() {
            window.print();
        }, 800);
    };
</script>
<?php } ?>
