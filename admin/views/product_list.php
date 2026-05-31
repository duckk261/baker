<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Product Management <span class="badge bg-danger fs-6 ms-2"></span></h2>
    <a href="index.php?page=add_product" class="btn btn-primary fw-bold"><i class="fas fa-plus me-2"></i>Thêm Sản Phẩm</a>
</div>
<div class="card shadow-sm border-0 p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr><th>ID</th><th>Hình ảnh</th><th>Tên sản phẩm</th><th>Giá bán</th><th>Hành động</th></tr>
            </thead>
            <tbody>
                <?php
                if ($products && mysqli_num_rows($products) > 0) {
                    while ($row = mysqli_fetch_assoc($products)) {
                        $p_id = $row['product_id'];
                        $p_name = $row['product_name'] ?? 'N/A';
                        $p_price = $row['price'] ?? 0;
                        $p_img = $row['image'] ?? 'default.jpg';

                        echo "<tr>
                                <td>#{$p_id}</td>
                                <td><img src='../assets/img/{$p_img}' style='width: 60px; height: 60px; object-fit: cover; border-radius: 8px;' onerror=\"this.onerror=null; this.src='https://placehold.co/60x60?text=No+Image';\"></td>
                                <td class='fw-bold'>{$p_name}</td>
                                <td class='text-danger fw-bold'>" . number_format((float)$p_price, 0, ',', '.') . " đ</td>
                                <td>
                                    <a href='index.php?page=product_detail&id={$p_id}' class='btn btn-sm btn-info text-white me-1'><i class='fas fa-eye'></i> Chi tiết</a>
                                    <a href='index.php?page=edit_product&id={$p_id}' class='btn btn-sm btn-outline-primary me-1'><i class='fas fa-edit'></i> Sửa</a>
                                    <a href='index.php?page=products&action=delete&id={$p_id}' class='btn btn-sm btn-outline-danger' onclick=\"return confirm('Xác nhận xóa {$p_name}?');\"><i class='fas fa-trash'></i> Xóa</a>
                                </td>
                              </tr>";
                    }
                } else { echo "<tr><td colspan='5' class='text-center py-4'>Chưa có sản phẩm nào.</td></tr>"; }
                ?>
            </tbody>
        </table>
    </div>
</div>