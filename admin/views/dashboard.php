<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold" style="color: #2c3e50;">Tổng Quan Hệ Thống <span class="badge bg-danger fs-6 ms-2"></span></h2>
    <div class="bg-white px-3 py-2 rounded shadow-sm border">
        <form method="GET" action="index.php" class="d-flex align-items-center">
            <input type="hidden" name="page" value="dashboard">
            <i class="fas fa-calendar-alt me-2 text-primary"></i> 
            <span class="me-2 fw-bold text-muted">Từ ngày:</span>
            <input type="date" name="start_date" class="form-control form-control-sm me-2" value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>" required>
            <span class="me-2 fw-bold text-muted">đến ngày:</span>
            <input type="date" name="end_date" class="form-control form-control-sm me-3" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>" required>
            <button type="submit" class="btn btn-primary btn-sm fw-bold"><i class="fas fa-filter me-1"></i> Lọc</button>
            <?php if(isset($_GET['start_date']) && !empty($_GET['start_date'])): ?>
                <a href="index.php?page=dashboard" class="btn btn-outline-secondary btn-sm ms-2 fw-bold"><i class="fas fa-times me-1"></i> Xóa lọc</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <a href="index.php?page=customers" class="text-decoration-none">
            <div class="card card-stat bg-primary text-white p-3 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div><p class="mb-1 opacity-75 text-white">Khách Hàng</p><h3 class="mb-0 fw-bold text-white"><?php echo $stats['customers']; ?></h3></div>
                    <i class="fas fa-users icon-large text-white"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="index.php?page=products" class="text-decoration-none">
            <div class="card card-stat bg-success text-white p-3 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div><p class="mb-1 opacity-75 text-white">Sản Phẩm</p><h3 class="mb-0 fw-bold text-white"><?php echo $stats['products']; ?></h3></div>
                    <i class="fas fa-box-open icon-large text-white"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="index.php?page=orders" class="text-decoration-none">
            <div class="card card-stat bg-warning text-dark p-3 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div><p class="mb-1 opacity-75 text-dark">Đơn Hàng</p><h3 class="mb-0 fw-bold text-dark"><?php echo $stats['orders']; ?></h3></div>
                    <i class="fas fa-shopping-bag icon-large text-dark"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="index.php?page=orders" class="text-decoration-none">
            <div class="card card-stat bg-danger text-white p-3 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div><p class="mb-1 opacity-75 text-white">Doanh Thu</p><h3 class="mb-0 fw-bold text-white"><?php echo number_format((float)$stats['revenue'], 0, ',', '.'); ?>đ</h3></div>
                    <i class="fas fa-wallet icon-large text-white"></i>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 pt-4 pb-0"><h5 class="fw-bold"><i class="fas fa-chart-bar me-2 text-primary"></i> Biểu đồ doanh thu <?php echo (!empty($_GET['start_date']) && !empty($_GET['end_date'])) ? 'theo ngày' : '12 tháng'; ?></h5></div>
            <div class="card-body"><canvas id="revenueChart"></canvas></div>
        </div>
    </div>
    
    <div class="col-md-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 pt-4 pb-3"><h5 class="fw-bold mb-0"><i class="fas fa-bolt me-2 text-warning"></i> Đơn hàng mới nhất</h5></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                       <thead class="table-light"><tr><th class="ps-3">ID</th><th>Tổng Cộng</th><th>Trạng Thái</th><th>Thao Tác</th></tr></thead>
                        <tbody>
                            <?php
                            if ($recent_orders && mysqli_num_rows($recent_orders) > 0) {
                                while ($r = mysqli_fetch_assoc($recent_orders)) {
                                    $id = $r['order_id'];
                                    $tien = $r['total_amount'] ?? 0;
                                    $stt = $r['status'] ?? 'Pending';
                                    
                                    $stt_lower = strtolower($stt);
                                    if ($stt_lower == 'hoan_tat') { $badge = 'bg-success'; } 
                                    elseif ($stt_lower == 'da_thanh_toan') { $badge = 'bg-primary'; } 
                                    elseif ($stt_lower == 'dang_giao') { $badge = 'bg-info text-dark'; } 
                                    elseif ($stt_lower == 'cho_duyet') { $badge = 'bg-warning text-dark'; } 
                                    else { $badge = 'bg-secondary'; }
                                    
                                    echo "<tr>
                                            <td class='ps-3 fw-bold'>#$id</td>
                                            <td class='text-danger fw-bold'>" . number_format((float)$tien, 0, ',', '.') . "đ</td>
                                            <td><span class='badge $badge'>$stt</span></td>
                                            <td>";
                                            
                                    // Bổ sung lại nút Duyệt nhanh gọi sang OrderController
                                    if ($stt_lower == 'cho_duyet') {
                                        echo "<a href='index.php?page=orders&action=approve&id={$id}' class='btn btn-xs btn-success py-0 px-2' style='font-size: 0.75rem; font-weight: bold;'><i class='fas fa-check'></i>Duyệt</a>";
                                    } elseif ($stt_lower == 'dang_giao') {
                                        echo "<a href='index.php?page=orders&action=complete&id={$id}' class='btn btn-xs btn-primary py-0 px-2' style='font-size: 0.75rem; font-weight: bold;'><i class='fas fa-box-open'></i>Hoàn Tất</a>";
                                    } else { 
                                        echo "<span class='text-muted small'>-</span>"; 
                                    }
                                    
                                    echo "</td></tr>";
                                }
                            } else { echo "<tr><td colspan='4' class='text-center py-3 text-muted'>Chưa có đơn hàng.</td></tr>"; }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2 mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 pt-4 pb-3"><h5 class="fw-bold mb-0"><i class="fas fa-star me-2 text-warning"></i> Sản phẩm bán chạy nhất</h5></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                       <thead class="table-light"><tr><th class="ps-3">Hình ảnh</th><th>Sản phẩm</th><th>Giá bán</th><th>Đã bán</th></tr></thead>
                        <tbody>
                            <?php
                            if (isset($best_selling_products) && $best_selling_products && mysqli_num_rows($best_selling_products) > 0) {
                                while ($p = mysqli_fetch_assoc($best_selling_products)) {
                                    $img = $p['image'] ?? 'default.jpg';
                                    $name = $p['product_name'] ?? 'N/A';
                                    $price = $p['price'] ?? 0;
                                    $sold = $p['total_sold'] ?? 0;
                                    
                                    echo "<tr>
                                            <td class='ps-3'><img src='../assets/img/{$img}' alt='{$name}' style='width: 50px; height: 50px; object-fit: cover;' class='rounded shadow-sm'></td>
                                            <td class='fw-bold text-primary'>{$name}</td>
                                            <td class='text-danger fw-bold'>" . number_format((float)$price, 0, ',', '.') . "đ</td>
                                            <td><span class='badge bg-success fs-6'>{$sold}</span></td>
                                          </tr>";
                                }
                            } else { echo "<tr><td colspan='4' class='text-center py-3 text-muted'>Chưa có dữ liệu sản phẩm bán chạy.</td></tr>"; }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
// Lấy dữ liệu mảng PHP từ controller để ném sang cho JavaScript vẽ biểu đồ
$chart_labels = isset($chart_data_from_db['labels']) ? $chart_data_from_db['labels'] : [];
$chart_data = isset($chart_data_from_db['data']) ? $chart_data_from_db['data'] : [];
?>
<script>
    // Ép kiểu mảng PHP sang JSON để Javascript hiểu được
    var labels = <?php echo json_encode($chart_labels); ?>;
    var data = <?php echo json_encode($chart_data); ?>;

    new Chart(document.getElementById('revenueChart').getContext('2d'), { 
        type: 'bar', 
        data: { 
            labels: labels, 
            datasets: [{ 
                label: 'Doanh thu thực tế (VNĐ)', 
                data: data, 
                backgroundColor: '#3b82f6', 
                borderRadius: 4 
            }] 
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>