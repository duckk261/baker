<?php 
include 'header.php'; 
include 'db_connect.php'; 
if (!isset($_SESSION['account_id'])) {
    echo "<script>alert('Please login to view your cart!'); window.location.href='login.php';</script>";
    exit();
}
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$item_counts = array_count_values($cart_items); 

$subtotal = 0;
$shipping = 30000; 
?>

<div class="container-xxl py-6">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
            <p class="text-primary text-uppercase mb-2">Shopping Cart</p>
            <h1 class="display-6 mb-4">Your Selected Items</h1>
        </div>

        <div class="row g-5">
            <div class="col-lg-8 wow fadeInUp" data-wow-delay="0.1s">
                <div class="table-responsive bg-light rounded p-4">
                    <table class="table align-middle" id="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if (empty($item_counts)) {
                                echo '<tr><td colspan="5" class="text-center py-5 fs-5">Your cart is empty!</td></tr>';
                            } else {
                                foreach ($item_counts as $product_id => $quantity) {
                                    $sql = "SELECT * FROM San_Pham WHERE Ma_SP = '$product_id'";
                                    $query = mysqli_query($conn, $sql);
                                    $item = mysqli_fetch_assoc($query);

                                    if($item) {
                                        $item_total = $item['Gia'] * $quantity; 
                                        $subtotal += $item_total;
                            ?>
                                        <tr id="row-<?php echo $product_id; ?>">
                                            <td class="d-flex align-items-center mt-3">
                                                <img src="img/product-1.jpg" alt="" class="rounded me-3" style="width: 70px; height: 70px; object-fit: cover;">
                                                <h6 class="mb-0"><?php echo $item['Ten_SP']; ?></h6> 
                                            </td>
                                            <td><?php echo number_format($item['Gia'], 0, ',', '.'); ?>đ</td> 
                                            <td>
                                                <input type="number" class="form-control form-control-sm text-center w-50" value="<?php echo $quantity; ?>" min="1" onchange="updateCartAJAX(<?php echo $product_id; ?>, this.value)">
                                            </td>
                                            <td class="text-primary fw-bold" id="row-total-<?php echo $product_id; ?>"><?php echo number_format($item_total, 0, ',', '.'); ?>đ</td>
                                            <td>
                                                <a href="javascript:void(0);" onclick="removeCartAJAX(<?php echo $product_id; ?>)" class="text-danger fs-5"><i class="fa fa-times"></i></a>
                                            </td>
                                        </tr>
                            <?php 
                                    }
                                }
                            } 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.5s">
                <div class="bg-light rounded p-4">
                    <h4 class="mb-4">Order Summary</h4>
                    <div class="d-flex justify-content-between mb-3">
                        <h6>Subtotal</h6>
                        <h6 id="summary-subtotal"><?php echo number_format($subtotal, 0, ',', '.'); ?>đ</h6>
                    </div>
                    <div class="d-flex justify-content-between border-bottom pb-3 mb-3">
                        <h6>Shipping</h6>
                        <h6 id="summary-shipping"><?php echo number_format($shipping, 0, ',', '.'); ?>đ</h6>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <h5>Total Price</h5>
                        <?php $final_total = ($subtotal > 0) ? $subtotal + $shipping : 0; ?>
                        <h5 class="text-primary fw-bold" id="summary-total"><?php echo number_format($final_total, 0, ',', '.'); ?>đ</h5>
                    </div>
                    <a href="checkout.php" class="btn btn-primary w-100 py-3">Proceed to Checkout</a>
                    <a href="product.php" class="d-block text-center mt-3 text-dark"><i class="fa fa-arrow-left me-2"></i>Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Hàm cập nhật số lượng
function updateCartAJAX(productId, quantity) {
    let formData = new FormData();
    formData.append('action', 'update');
    formData.append('id', productId);
    formData.append('qty', quantity);

    fetch('api/cart_action.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                // Cập nhật lại số tiền của cái bánh đó
                document.getElementById('row-total-' + productId).innerText = data.row_total;
                // Cập nhật lại tổng hóa đơn
                updateOrderSummary(data);
            }
        });
}

// Hàm xóa sản phẩm
function removeCartAJAX(productId) {
    if(!confirm('Are you sure you want to remove this item from your cart?')) return;

    let formData = new FormData();
    formData.append('action', 'remove');
    formData.append('id', productId);

    fetch('api/cart_action.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                let row = document.getElementById('row-' + productId);
                if(row) row.remove(); 
                updateOrderSummary(data);
                if(data.is_empty) {
                    document.querySelector('#cart-table tbody').innerHTML = '<tr><td colspan="5" class="text-center py-5 fs-5">Your cart is empty!</td></tr>';
                }
            }
        });
}

// Hàm dùng chung để cập nhật mấy con số bên bảng hóa đơn
function updateOrderSummary(data) {
    document.getElementById('summary-subtotal').innerText = data.subtotal;
    document.getElementById('summary-tax').innerText = data.tax_amount; 
    document.getElementById('summary-total').innerText = data.final_total;
    
    let badge = document.getElementById('cart-badge');
    if(badge) badge.innerText = data.cart_count;
    
    if(data.is_empty) {
        document.getElementById('summary-shipping').innerText = '0đ';
        document.getElementById('summary-tax').innerText = '0đ';
    }
}
</script>

<?php include 'footer.php'; ?>