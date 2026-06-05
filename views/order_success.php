<?php
if (!isset($_SESSION['account_id'])) {
    header("Location: index.php?page=login");
    exit();
}

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
include 'header.php';
?>

<div class="container-xxl py-6">
    <div class="container">
        <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 700px;">
            <div class="bg-light rounded p-5">
                <h1 class="display-6 mb-3 text-success">Đặt hàng thành công!</h1>
                <p class="mb-3">
                    Cảm ơn bạn đã mua sắm. Đơn hàng của bạn đã được tạo
                    <?php if ($order_id > 0): ?>
                        với mã <span class="fw-bold">#<?php echo $order_id; ?></span>.
                    <?php else: ?>
                        thành công.
                    <?php endif; ?>
                </p>
                <p class="text-muted mb-4">
                    Nếu bạn chọn <span class="fw-bold">Chuyển Khoản Ngân Hàng</span>, chúng tôi sẽ xử lý đơn hàng sau khi nhận được thanh toán.
                    Nếu bạn chọn <span class="fw-bold">COD</span>, cửa hàng sẽ gọi xác nhận trước khi giao hàng.
                </p>

                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    <a class="btn btn-primary px-4 py-2" href="index.php?page=product">Tiếp Tục Mua Sắm</a>
                    <a class="btn btn-outline-secondary px-4 py-2" href="index.php?page=home">Về Trang Chủ</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

