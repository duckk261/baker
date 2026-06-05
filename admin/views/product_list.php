<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Product Management <span class="badge bg-danger fs-6 ms-2"></span></h2>
    <form method="GET" action="index.php" class="d-flex align-items-center gap-2">
        <input type="hidden" name="page" value="products">
        <input type="text" name="search" class="form-control border-primary" placeholder="Enter product ID or name..." style="min-width: 250px;" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit" class="btn btn-primary fw-bold text-nowrap"><i class="fas fa-search"></i> Search</button>
        <?php if(isset($_GET['search']) && $_GET['search'] != ''): ?>
            <a href="index.php?page=products" class="btn btn-outline-danger fw-bold text-nowrap"><i class="fas fa-times"></i> Clear Filter</a>
        <?php endif; ?>
    </form>
    <a href="index.php?page=add_product" class="btn btn-primary fw-bold"><i class="fas fa-plus me-2"></i>Add Product</a>
</div>
<div class="card shadow-sm border-0 p-4">
    <div class="table-responsive">
       <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th class="text-center">Stock</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($products && mysqli_num_rows($products) > 0) {
                    while ($row = mysqli_fetch_assoc($products)) {
                        $p_id = $row['product_id'];
                        $p_name = $row['product_name'] ?? 'N/A';
                        $p_price = $row['price'] ?? 0;
                        $p_img = $row['image'] ?? 'default.jpg';

                        $p_status = isset($row['status']) ? (int)$row['status'] : 1;
                        $status_badge = ($p_status == 1) 
                            ? "<span class='badge bg-success px-3 py-2 rounded-1'>Visible</span>" 
                            : "<span class='badge bg-secondary px-3 py-2 rounded-1'>Hidden</span>";

    
                        $qty = isset($row['stock_quantity']) ? (int)$row['stock_quantity'] : 0;
                        if ($qty > 0) {
                            $stock_badge = "<span class='badge bg-info text-dark px-3 py-2 rounded-1 fw-bold'>In Stock: {$qty}</span>";
                        } else {
                            $stock_badge = "<span class='badge bg-danger px-3 py-2 rounded-1 fw-bold'>Out of Stock</span>";
                        }

                        echo "<tr>
                                <td>#{$p_id}</td>
                                <td><img src='../assets/img/{$p_img}' style='width: 60px; height: 60px; object-fit: cover; border-radius: 8px;' onerror=\"this.onerror=null; this.src='https://placehold.co/60x60?text=No+Image';\"></td>
                                <td class='fw-bold text-dark'>{$p_name}</td>
                                <td class='fw-bold' style='color: #c4a16b;'>" . number_format((float)$p_price, 0, ',', '.') . " ₫</td>
                                
                                <td class='text-center'>{$stock_badge}</td>
                                
                                <td class='text-center'>{$status_badge}</td>
                                <td class='text-center'>
                                    <a href='index.php?page=product_detail&id={$p_id}' class='btn btn-sm btn-info text-white rounded-1 me-1' title='Details'><i class='fas fa-eye'></i></a>
                                    <a href='index.php?page=edit_product&id={$p_id}' class='btn btn-sm btn-warning text-dark rounded-1 me-1' title='Edit'><i class='fas fa-edit'></i></a>
                                    <a href='index.php?page=products&action=delete&id={$p_id}' class='btn btn-sm btn-danger rounded-1' onclick=\"return confirm('Confirm delete {$p_name}?');\" title='Delete'><i class='fas fa-trash-alt'></i></a>
                                </td>
                              </tr>";
                    }
                } else { 
                    echo "<tr><td colspan='7' class='text-center py-4 text-muted'>No products found.</td></tr>"; 
                }
                ?>
            </tbody>
        </table>
    </div>
</div>