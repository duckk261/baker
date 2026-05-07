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
            <div class="bg-white shadow-lg rounded p-5 border-top border-5 border-primary">
                <div class="mb-4">
                    <i class="fa fa-check-circle fa-4x text-success"></i>
                </div>
                <h1 class="display-6 mb-3 text-dark">Order placed successfully!</h1>
                <p class="mb-3">
                    Thank you for your purchase. Your order has been created
                    <?php if ($order_id > 0): ?>
                        with ID <span class="fw-bold">#<?php echo $order_id; ?></span>.
                    <?php else: ?>
                        successfully.
                    <?php endif; ?>
                </p>

                <div class="alert alert-info border-0 shadow-sm mb-4 py-4">
                    <h5 class="alert-heading mb-2">
                        <i class="fa fa-envelope-open-text me-2"></i>Confirmation Sent!
                    </h5>
                    <p class="mb-0">A confirmation email has been sent to your inbox.</p>
                    <p class="text-muted small mb-0 mt-2">If you do not receive it, please check your spam folder or contact our support team.</p>
                </div>

                <p class="text-muted mb-4">
                    If you selected <span class="fw-bold">Bank Transfer</span>, stock has been updated for this paid order.
                    If you selected <span class="fw-bold">COD</span>, the store will confirm the order before delivery.
                </p>

                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    <a class="btn btn-primary px-4 py-2" href="index.php?page=product">Continue shopping</a>
                    <a class="btn btn-outline-secondary px-4 py-2" href="index.php?page=home">Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>