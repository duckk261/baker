<?php 
include 'header.php'; 
include 'db_connect.php'; 

// 1. KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['account_id'])) {
    echo "<script>alert('Please login to checkout!'); window.location.href='login.php';</script>";
    exit();
}

$ma_kh = $_SESSION['account_id'];
$user_info = null;

// Truy vấn thông tin chi tiết từ bảng khach_hang để tự động điền form
$sql_user = "SELECT * FROM khach_hang WHERE Ma_KH = '$ma_kh'";
$res_user = mysqli_query($conn, $sql_user);
if ($res_user && mysqli_num_rows($res_user) > 0) {
    $user_info = mysqli_fetch_assoc($res_user);
}

// 2. LẤY DỮ LIỆU GIỎ HÀNG
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Nếu giỏ hàng trống, quay về trang sản phẩm
if (empty($cart_items)) {
    echo "<script>alert('Your cart is empty!'); window.location.href='product.php';</script>";
    exit();
}

// Gộp sản phẩm để hiển thị
$item_counts = array_count_values($cart_items); 
$subtotal = 0;
$shipping = 30000; 
$tax_rate = 0.08;
?>

<div class="container-xxl py-6">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
            <p class="text-primary text-uppercase mb-2">Checkout</p>
            <h1 class="display-6 mb-4">Complete Your Order</h1>
        </div>

        <form action="process_order.php" method="POST">
            <div class="row g-5">
                <div class="col-lg-7 wow fadeInUp" data-wow-delay="0.1s">
                    <h4 class="mb-4">Shipping Address</h4>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" name="fullname" 
                                       value="<?php echo isset($user_info['Ten_KH']) ? $user_info['Ten_KH'] : ''; ?>" required>
                                <label for="name">Full Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo isset($user_info['Email']) ? $user_info['Email'] : ''; ?>">
                                <label for="email">Email Address (Optional)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="phone" name="phone" 
                                       value="<?php echo isset($user_info['Dien_thoai']) ? $user_info['Dien_thoai'] : ''; ?>" required>
                                <label for="phone">Phone Number</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" id="address" name="address" style="height: 100px" required><?php echo isset($user_info['Dia_chi']) ? $user_info['Dia_chi'] : ''; ?></textarea>
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
                    <div class="bg-light p-4 rounded">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment" id="cod" value="COD" checked>
                            <label class="form-check-label" for="cod">Cash on Delivery (COD)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment" id="bank" value="Bank Transfer">
                            <label class="form-check-label" for="bank">Bank Transfer</label>
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
                                    foreach ($item_counts as $product_id => $quantity) {
                                        $sql = "SELECT * FROM San_Pham WHERE Ma_SP = '$product_id'";
                                        $query = mysqli_query($conn, $sql);
                                        $item = mysqli_fetch_assoc($query);

                                        if($item) {
                                            $item_total = $item['Gia'] * $quantity; 
                                            $subtotal += $item_total;
                                    ?>
                                            <tr>
                                                <td>
                                                    <span class="fw-bold"><?php echo $item['Ten_SP']; ?></span> 
                                                    <span class="text-muted">x <?php echo $quantity; ?></span>
                                                </td>
                                                <td class="text-end"><?php echo number_format($item_total, 0, ',', '.'); ?>đ</td>
                                            </tr>
                                    <?php 
                                        }
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
    <?php $tax_amount = $subtotal * $tax_rate; ?>
    <h6><?php echo number_format($tax_amount, 0, ',', '.'); ?>đ</h6>
</div>
<div class="d-flex justify-content-between border-bottom pb-3 mb-3">
    <h6>Shipping</h6>
    <h6><?php echo number_format($shipping, 0, ',', '.'); ?>đ</h6>
</div>
<div class="d-flex justify-content-between mb-4">
    <h5>Total Price</h5>
    <?php $final_total = $subtotal + $tax_amount + $shipping; ?>
    <h5 class="text-primary fw-bold"><?php echo number_format($final_total, 0, ',', '.'); ?>đ</h5>
</div>
                        <button type="submit" class="btn btn-primary w-100 py-3">Place Order</button>
                        <a href="cart.php" class="d-block text-center mt-3 text-muted small"><i class="fa fa-chevron-left me-2"></i>Back to Cart</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>