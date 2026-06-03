<?php 
require_once 'app/models/UserModel.php';
require_once 'app/controllers/CartController.php';
require_once 'app/classes/Database.php';
$db = Database::getInstance();
// 1. KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['account_id'])) {
    echo "<script>alert('Please login to checkout!'); window.location.href='index.php?page=login';</script>";
    exit();
}

// Lấy thông tin user để điền sẵn vào Form
$userModel = new UserModel($db);
$user_info = $userModel->getUserProfile($_SESSION['account_id']);

// 2. LẤY DỮ LIỆU GIỎ HÀNG TỪ CONTROLLER 
$cartController = new CartController($db);
$cart_data = $cartController->getCartDetails();

$cart_details = $cart_data['details'];
$subtotal = $cart_data['subtotal'];
$tax_amount = $cart_data['tax_amount'];
$shipping = $cart_data['shipping'];
// ... code cũ của ông ...
$final_total = $cart_data['final_total'];

$bank_code = "Techcombank"; 
$stk = "19071927305017"; 
$ten_chu_tk = "Hoang Minh Thu"; 
$sdt_khach = isset($user_info['phone_number']) ? $user_info['phone_number'] : 'KhachHang';
$noi_dung_ck = "Thanh toan banh " . $sdt_khach; 

// Nối chuỗi để tạo ra link API
$link_qr_tu_dong = "https://img.vietqr.io/image/{$bank_code}-{$stk}-compact2.png?amount={$final_total}&addInfo=" . urlencode($noi_dung_ck) . "&accountName=" . urlencode($ten_chu_tk);
// Nếu giỏ hàng trống, đuổi về trang sản phẩm
if (empty($cart_details)) {
    echo "<script>alert('Your cart is empty!'); window.location.href='index.php?page=product';</script>";
    exit();
}

include 'header.php'; 
?>

<div class="container-xxl py-6">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
            <p class="text-primary text-uppercase mb-2">Checkout</p>
            <h1 class="display-6 mb-4">Complete Your Order</h1>
        </div>

        <form action="index.php?page=process_order" method="POST">
            <div class="row g-5">
                <div class="col-lg-7 wow fadeInUp" data-wow-delay="0.1s">
                    <h4 class="mb-4">Shipping Address</h4>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" name="fullname" 
                                       value="<?php echo isset($user_info['full_name']) ? $user_info['full_name'] : ''; ?>" required>
                                <label for="name">Full Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo isset($user_info['email']) ? $user_info['email'] : ''; ?>">
                                <label for="email">Email Address (Optional)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="phone" name="phone" 
                                       value="<?php echo isset($user_info['phone_number']) ? $user_info['phone_number'] : ''; ?>" required>
                                <label for="phone">Phone Number</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" id="address" name="address" style="height: 100px" required><?php echo isset($user_info['address']) ? $user_info['address'] : ''; ?></textarea>
                                <label for="address">Full Shipping Address</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="Notes" id="notes" name="notes" style="height: 80px"></textarea>
                                <label for="notes">Order Notes (Optional)</label>
                            </div>
                        </div>
                    </div>
                    
                   <h4 class="mt-5 mb-4">Payment Method</h4>
                    <div class="bg-light p-4 rounded mb-4">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment" id="cod" value="COD" checked>
                            <label class="form-check-label" for="cod">Cash on Delivery (COD)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment" id="bank" value="Bank Transfer">
                            <label class="form-check-label" for="bank">Bank Transfer</label>
                        </div>
                    </div>

                    <div id="qr-code-section" class="d-none bg-light p-4 rounded text-center border border-primary mb-4 shadow-sm">
                        <h5 class="fw-bold text-primary mb-3">Quét mã QR để thanh toán</h5>
                        
                        <img src="<?php echo $link_qr_tu_dong; ?>" alt="Mã QR Thanh Toán" class="img-fluid mb-3 rounded" style="max-width: 250px; border: 2px solid #c4a16b;">
                        
                        <div class="text-start bg-white p-3 rounded border">
                            <p class="mb-2"><strong>Ngân hàng:</strong> Techcombank</p>
                            <p class="mb-2"><strong>Số tài khoản:</strong> 19071927305017</p>
                            <p class="mb-2"><strong>Chủ tài khoản:</strong> Hoang Minh Thu</p>
                            <p class="mb-0 text-danger fw-bold"><strong>Nội dung CK:</strong> Thanh toan don hang <?php echo isset($user_info['phone_number']) ? $user_info['phone_number'] : ''; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="bg-light rounded p-4">
                        <h4 class="mb-4">Your Order</h4>
                        <div class="table-responsive mb-4">
                            <table class="table table-borderless">
                                <thead>
                                    <tr class="border-bottom">
                                        <th>Product</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    // Vòng lặp lấy trực tiếp từ mảng $cart_details đã được Controller tính toán
                                    foreach ($cart_details as $item) {
                                    ?>
                                        <tr>
                                            <td>
                                                <span class="fw-bold"><?php echo $item['product_name']; ?></span> 
                                                <span class="text-muted">x <?php echo $item['quantity']; ?></span>
                                            </td>
                                            <td class="text-end"><?php echo number_format($item['item_total'], 0, ',', '.'); ?>đ</td>
                                        </tr>
                                    <?php 
                                    } 
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <h6>Subtotal</h6>
                            <h6><?php echo number_format($subtotal, 0, ',', '.'); ?>đ</h6>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <h6>VAT (8%)</h6>
                            <h6><?php echo number_format($tax_amount, 0, ',', '.'); ?>đ</h6>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-3 mb-3">
                            <h6>Shipping</h6>
                            <h6><?php echo number_format($shipping, 0, ',', '.'); ?>đ</h6>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <h5>Total Price</h5>
                            <h5 class="text-primary fw-bold"><?php echo number_format($final_total, 0, ',', '.'); ?>đ</h5>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-3">Place Order</button>
                        <a href="index.php?page=cart" class="d-block text-center mt-3 text-muted small"><i class="fa fa-chevron-left me-2"></i>Back to Cart</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Bắt lấy cái form, khu vực QR và các nút
    const form = document.querySelector('form[action="index.php?page=process_order"]');
    const qrSection = document.getElementById('qr-code-section');
    const bankRadio = document.getElementById('bank');
    const codRadio = document.getElementById('cod');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    let isWaitingForPayment = false;

    // 1. Xử lý khi khách quay xe: Đang chọn Chuyển khoản lại bấm về COD
    codRadio.addEventListener('change', function() {
        qrSection.classList.add('d-none'); // Giấu QR đi
        isWaitingForPayment = false; // Reset trạng thái
        // Trả lại nút Place Order màu vàng nguyên bản
        submitBtn.innerHTML = "Place Order";
        submitBtn.className = "btn btn-primary w-100 py-3"; 
    });

    // Bấm sang Bank Transfer thì giấu đi nếu nó đang hiện (reset)
    bankRadio.addEventListener('change', function() {
        qrSection.classList.add('d-none');
        isWaitingForPayment = false;
        submitBtn.innerHTML = "Place Order";
        submitBtn.className = "btn btn-primary w-100 py-3";
    });

    // 2. Can thiệp vào khoảnh khắc khách bấm nút "Place Order"
    form.addEventListener('submit', function(e) {
        // Nếu khách chọn Chuyển khoản VÀ mã QR chưa được bung ra
        if (bankRadio.checked && !isWaitingForPayment) {
            e.preventDefault(); // Chặn! KHÔNG CHO GỬI ĐƠN HÀNG ĐI VỘI
            
            qrSection.classList.remove('d-none'); // Bung mã QR ra
            isWaitingForPayment = true; // Đánh dấu là đang đợi khách quét mã
            
            // Biến hình nút bấm thành màu xanh báo hiệu hoàn tất
            submitBtn.innerHTML = "<i class='fas fa-check-circle me-2'></i>Tôi đã chuyển khoản (Hoàn tất)";
            submitBtn.className = "btn btn-success w-100 py-3 fw-bold fs-5 shadow";
            
            // Tự động trượt nhẹ màn hình xuống đúng chỗ mã QR cho khách dễ nhìn
            qrSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
});
</script>
<?php include 'footer.php'; ?>