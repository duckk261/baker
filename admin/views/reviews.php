<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Product Reviews <span class="badge bg-danger fs-6 ms-2"></span></h2>
    </div>

    <div class="card border-0 shadow-sm p-4">
        <div class="mb-4" style="max-width: 400px;">
            <form method="GET" action="index.php" class="input-group">
                <input type="hidden" name="page" value="reviews">
                <input type="text" name="search" class="form-control border-primary" placeholder="Search by product name..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
    <tr>
        <th style="width: 10%;">ID</th>
        <th style="width: 30%;">Tên Sản Phẩm</th>
        <th style="width: 15%;">Hình Ảnh</th>
<th>
            <a href="?page=reviews&sort=rating&order=<?php echo ($order == 'DESC') ? 'ASC' : 'DESC'; ?>" class="text-decoration-none text-dark">
                Average Rating <i class="fas fa-sort"></i>
            </a>
        </th>
        <th>
            <a href="?page=reviews&sort=total_reviews&order=<?php echo ($order == 'DESC') ? 'ASC' : 'DESC'; ?>" class="text-decoration-none text-dark">
                Total Reviews <i class="fas fa-sort"></i>
            </a>
        </th>
        <th style="width: 10%;" class="text-center">Details</th>
    </tr>
</thead>
                <tbody>
                    <?php
                    if ($reviews_data && mysqli_num_rows($reviews_data) > 0) {
                        while ($row = mysqli_fetch_assoc($reviews_data)) {
                            echo "<tr>
                                    <td>#{$row['product_id']}</td>
                                    <td class='fw-bold'>{$row['product_name']}</td>
                                    <td><img src='../assets/img/{$row['image']}' style='width: 50px; height: 50px; object-fit: cover; border-radius: 8px;'></td>
                                    <td class='text-center fw-bold text-warning'>" . number_format($row['avg_rating'], 1) . "/5.0</td>
                                    <td class='text-center'>{$row['total_reviews']}</td>
                                    <td class='text-center'>
                                        <a href='index.php?page=review_detail&id={$row['product_id']}' class='btn btn-sm btn-info text-white rounded-1'>
                                            <i class='fas fa-info-circle'></i>
                                        </a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center py-4'>No reviews found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>