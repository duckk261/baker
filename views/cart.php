<?php include 'header.php'; ?>
<div class="container-xxl py-6">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                <table class="table align-middle" id="cart-table">
                    <thead><tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th>Remove</th></tr></thead>
                    <tbody>
                        <?php 
                        if (empty($cart_details)) {
                            echo '<tr><td colspan="5" class="text-center py-5 fs-5">Your cart is empty!</td></tr>';
                        } else {
                            foreach ($cart_details as $item) {
                        ?>
                            <tr id="row-<?php echo $item['product_id']; ?>">
                                <td><h6 class="mb-0"><?php echo $item['product_name']; ?></h6></td>
                                <td><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</td> 
                                <td>
                                    <input type="number" class="form-control text-center w-50" value="<?php echo $item['quantity']; ?>" min="1" onchange="updateCartAJAX(<?php echo $item['product_id']; ?>, this.value)">
                                </td>
                                <td id="row-total-<?php echo $item['product_id']; ?>"><?php echo number_format($item['item_total'], 0, ',', '.'); ?>đ</td>
                                <td><a href="javascript:void(0);" onclick="removeCartAJAX(<?php echo $item['product_id']; ?>)" class="text-danger"><i class="fa fa-times"></i></a></td>
                            </tr>
                        <?php }} ?>
                    </tbody>
                </table>
            </div>

            <div class="col-lg-4">
                <div class="bg-light rounded p-4">
                    <h4 class="mb-4">Order Summary</h4>
                    <div class="d-flex justify-content-between mb-3"><h6>Subtotal</h6><h6 id="summary-subtotal"><?php echo number_format($subtotal ?? 0, 0, ',', '.'); ?>đ</h6></div>
                    <div class="d-flex justify-content-between mb-3"><h6>VAT (8%)</h6><h6 id="summary-tax"><?php echo number_format($tax_amount ?? 0, 0, ',', '.'); ?>đ</h6></div>
                    <div class="d-flex justify-content-between mb-3"><h6>Shipping</h6><h6 id="summary-shipping"><?php echo number_format($shipping ?? 30000, 0, ',', '.'); ?>đ</h6></div>
                    <div class="d-flex justify-content-between mb-4"><h5>Total</h5><h5 id="summary-total"><?php echo number_format($final_total ?? 0, 0, ',', '.'); ?>đ</h5></div>
                    <a href="index.php?page=checkout" class="btn btn-primary w-100 py-3">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateCartAJAX(productId, quantity) {
    let formData = new FormData();
    formData.append('action', 'update'); formData.append('id', productId); formData.append('quantity', quantity);
    fetch('index.php?page=cart&action=update', { method: 'POST', body: formData })
        .then(res => res.json()).then(data => { if(data.status === 'success') { document.getElementById('row-total-' + productId).innerText = data.row_total; updateOrderSummary(data); }});
}
function removeCartAJAX(productId) {
    let formData = new FormData();
    formData.append('action', 'remove'); formData.append('id', productId);
    fetch('index.php?page=cart&action=update', { method: 'POST', body: formData })
        .then(res => res.json()).then(data => { if(data.status === 'success') { document.getElementById('row-' + productId).remove(); updateOrderSummary(data); }});
}
function updateOrderSummary(data) {
    document.getElementById('summary-subtotal').innerText = data.subtotal;
    document.getElementById('summary-tax').innerText = data.tax_amount; 
    document.getElementById('summary-total').innerText = data.final_total;
    let badge = document.getElementById('cart-badge'); if(badge) badge.innerText = data.cart_count;
}
</script>
<?php include 'footer.php'; ?>