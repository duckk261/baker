<?php include 'header.php'; ?>
<div class="container-xxl py-6">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                <table class="table align-middle" id="cart-table">
                    <thead><tr><th>Sản Phẩm</th><th>Giá</th><th>Số Lượng</th><th>Tổng Cộng</th><th>Xóa</th></tr></thead>
                    <tbody>
                        <?php 
                        if (empty($cart_details)) {
                            echo '<tr><td colspan="5" class="text-center py-5 fs-5">Giỏ hàng của bạn đang trống!</td></tr>';
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
                    <h4 class="mb-4">Tổng Đơn Hàng</h4>
                    <div class="d-flex justify-content-between mb-3"><h6>Tạm Tính</h6><h6 id="summary-subtotal"><?php echo number_format($subtotal ?? 0, 0, ',', '.'); ?>đ</h6></div>
                    <div class="d-flex justify-content-between mb-3"><h6>VAT (8%)</h6><h6 id="summary-tax"><?php echo number_format($tax_amount ?? 0, 0, ',', '.'); ?>đ</h6></div>
                    <div class="d-flex justify-content-between mb-3"><h6>Phí Vận Chuyển</h6><h6 id="summary-shipping"><?php echo number_format($shipping ?? 30000, 0, ',', '.'); ?>đ</h6></div>
                    <div class="d-flex justify-content-between mb-4"><h5>Tổng Cộng</h5><h5 id="summary-total"><?php echo number_format($final_total ?? 0, 0, ',', '.'); ?>đ</h5></div>
                    <a href="index.php?page=checkout" class="btn btn-primary w-100 py-3">Tiến Hành Thanh Toán</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// 1. Hàm Cập nhật số lượng
function updateCartAJAX(productId, quantity) {
    let formData = new FormData();
    formData.append('action', 'update'); 
    formData.append('id', productId); 
    formData.append('quantity', quantity);
    
    fetch('index.php?page=cart&action=update', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => { 
            if(data.status === 'success') { 
                // Cập nhật tiền của TỪNG DÒNG
                document.getElementById('row-total-' + productId).innerText = data.row_total; 
                
                // Gọi hàm cập nhật Tổng bill ở dưới
                updateOrderSummary(data); 
                
                // Tự động nhảy số trên cái giỏ hàng góc phải trên cùng (Header)
                const badge = document.getElementById('cart-badge');
                if(badge && data.cart_count !== undefined) badge.innerText = data.cart_count;

            } else if (data.status === 'error_stock') {
                Swal.fire({title: 'Thông báo', text: data.message, confirmButtonColor: '#c4a16b', icon: 'info'});
                location.reload(); 
            } else {
                Swal.fire({title: 'Thông báo', text: data.message || 'Lỗi không xác định', confirmButtonColor: '#c4a16b', icon: 'info'});
            }
        })
        .catch(error => console.error('Error:', error));
}

// 2. Hàm Xóa sản phẩm khỏi giỏ
function removeCartAJAX(productId) {
    Swal.fire({
        title: 'Xác nhận',
        text: 'Bạn có chắc muốn bỏ bánh này ra khỏi giỏ hàng?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#c4a16b',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Đồng ý',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            let formData = new FormData();
            formData.append('action', 'remove');
            formData.append('id', productId);

            fetch('index.php?page=cart&action=remove', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'success') {
                        let row = document.getElementById('row-' + productId);
                        if(row) {
                            row.style.transition = "opacity 0.5s";
                            row.style.opacity = 0;
                            setTimeout(() => row.remove(), 500);
                        }
                        
                        updateOrderSummary(data);
                        
                        const badge = document.getElementById('cart-badge');
                        if(badge && data.cart_count !== undefined) badge.innerText = data.cart_count;

                        if(data.cart_count == 0) location.reload();
                    } else {
                        Swal.fire({title: 'Thông báo', text: data.message || 'Lỗi không thể xóa sản phẩm.', confirmButtonColor: '#c4a16b', icon: 'info'});
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    });
}

// 3. Hàm tự động tính lại Bảng Order Summary (Tất cả tiền đình)
function updateOrderSummary(data) {
    if(data.subtotal) document.getElementById('summary-subtotal').innerText = data.subtotal;
    if(data.tax) document.getElementById('summary-tax').innerText = data.tax;
    if(data.total) document.getElementById('summary-total').innerText = data.total;}
</script>
<?php include 'footer.php'; ?>