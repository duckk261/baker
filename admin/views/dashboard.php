<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold" style="color: #2c3e50;">Dashboard Overview <span class="badge bg-danger fs-6 ms-2"></span></h2>
    <div class="text-muted bg-white px-4 py-2 rounded shadow-sm border fw-bold">
        <i class="fas fa-calendar-alt me-2 text-primary"></i> Today: <?php echo date('d/m/Y'); ?>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <a href="index.php?page=customers" class="text-decoration-none">
            <div class="card card-stat bg-primary text-white p-3 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div><p class="mb-1 opacity-75 text-white">Customers</p><h3 class="mb-0 fw-bold text-white"><?php echo $stats['customers']; ?></h3></div>
                    <i class="fas fa-users icon-large text-white"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="index.php?page=products" class="text-decoration-none">
            <div class="card card-stat bg-success text-white p-3 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div><p class="mb-1 opacity-75 text-white">Products</p><h3 class="mb-0 fw-bold text-white"><?php echo $stats['products']; ?></h3></div>
                    <i class="fas fa-box-open icon-large text-white"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="index.php?page=orders" class="text-decoration-none">
            <div class="card card-stat bg-warning text-dark p-3 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div><p class="mb-1 opacity-75 text-dark">Orders</p><h3 class="mb-0 fw-bold text-dark"><?php echo $stats['orders']; ?></h3></div>
                    <i class="fas fa-shopping-bag icon-large text-dark"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="index.php?page=orders" class="text-decoration-none">
            <div class="card card-stat bg-danger text-white p-3 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div><p class="mb-1 opacity-75 text-white">Revenue</p><h3 class="mb-0 fw-bold text-white"><?php echo number_format((float)$stats['revenue'], 0, ',', '.'); ?>đ</h3></div>
                    <i class="fas fa-wallet icon-large text-white"></i>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 pt-4 pb-0"><h5 class="fw-bold"><i class="fas fa-chart-bar me-2 text-primary"></i> Biểu đồ doanh thu 6 tháng</h5></div>
            <div class="card-body"><canvas id="revenueChart"></canvas></div>
        </div>
    </div>
    
    <div class="col-md-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 pt-4 pb-3"><h5 class="fw-bold mb-0"><i class="fas fa-bolt me-2 text-warning"></i> Đơn hàng mới nhất</h5></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                       <thead class="table-light"><tr><th class="ps-3">ID</th><th>Total</th><th>Status</th><th>Actions</th></tr></thead>
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
                                        echo "<a href='index.php?page=orders&action=approve&id={$id}' class='btn btn-xs btn-success py-0 px-2' style='font-size: 0.75rem; font-weight: bold;'><i class='fas fa-check'></i>Approve</a>";
                                    } elseif ($stt_lower == 'dang_giao') {
                                        echo "<a href='index.php?page=orders&action=complete&id={$id}' class='btn btn-xs btn-primary py-0 px-2' style='font-size: 0.75rem; font-weight: bold;'><i class='fas fa-box-open'></i> Complete</a>";
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
<?php
// Chuẩn bị dữ liệu mảng PHP để ném sang cho JavaScript vẽ biểu đồ
$chart_labels = [];
$chart_data = [];
for ($i = 1; $i <= 12; $i++) {
    $chart_labels[] = "Tháng $i";
    $chart_data[] = $monthly_revenue[$i]; // Lấy số tiền tương ứng của tháng đó
}
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