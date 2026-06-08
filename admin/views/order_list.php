<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">Quản Lý Đơn Hàng<span class="badge bg-danger fs-6 ms-2"></span></h2>
    <form method="GET" action="index.php" class="d-flex align-items-center gap-2">
        <input type="hidden" name="page" value="orders">
        <input type="date" name="filter_date" class="form-control border-primary" style="max-width: 160px;" value="<?php echo $filter_date; ?>">
        <button type="submit" class="btn btn-primary fw-bold text-nowrap"><i class="fas fa-filter"></i> Lọc</button>
        <?php if($filter_date != ''): ?>
            <a href="index.php?page=orders" class="btn btn-outline-danger fw-bold text-nowrap"><i class="fas fa-times"></i> Hủy</a>
        <?php endif; ?>
    </form>
</div>
<div class="card shadow-sm border-0 p-4">
    <div class="table-responsive">
     <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th class="text-center" style="width: 15%;">Mã Đơn</th>
                    <th class="text-center" style="width: 25%;">Tổng Tiền</th>
                    <th class="text-center" style="width: 20%;">Trạng Thái</th>
                    <th class="text-center" style="width: 40%;">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($orders && mysqli_num_rows($orders) > 0) {
                    while ($row = mysqli_fetch_assoc($orders)) {
                        $o_id = $row['order_id'];
                        $total = $row['total_amount'] ?? 0;
                        $status = $row['status'] ?? 'Pending';
                        
                        $status_lower = strtolower($status);
                        if ($status_lower == 'hoan_tat') { $badge_class = 'bg-success'; } 
                        elseif ($status_lower == 'da_thanh_toan') { $badge_class = 'bg-primary'; } 
                        elseif ($status_lower == 'dang_giao') { $badge_class = 'bg-info text-dark'; } 
                        elseif ($status_lower == 'cho_duyet') { $badge_class = 'bg-warning text-dark'; } 
                        elseif ($status_lower == 'da_huy') { $badge_class = 'bg-secondary'; }
                        else { $badge_class = 'bg-secondary'; }
                        
                        // 2. Thêm class ps-5 vào thẻ td để đẩy các nút thụt vào trong
                        echo "<tr>
                                <td class='fw-bold text-center'>#{$o_id}</td>
                                <td class='text-danger fw-bold text-center'>" . number_format((float)$total, 0, ',', '.') . " đ</td>
                                <td class='text-center'><span class='badge {$badge_class}'>{$status}</span></td>
                                <td class='ps-5'> 
                                    <div class='d-flex justify-content-start align-items-center gap-2'>
                                        <a href='index.php?page=order_detail&id={$o_id}' class='btn btn-sm btn-info text-white text-decoration-none'><i class='fas fa-eye'></i> Xem Chi Tiết</a>";
                                      
                        if ($status_lower == 'cho_duyet') {
                            echo "<a href='index.php?page=orders&action=approve&id={$o_id}' class='btn btn-sm btn-success'><i class='fas fa-check'></i> Duyệt</a>";
                            echo "<a href='javascript:void(0);' class='btn btn-sm btn-danger' onclick='cancelOrder({$o_id})'><i class='fas fa-times'></i> Hủy</a>";
                        } elseif ($status_lower == 'dang_giao') {
                            echo "<a href='index.php?page=orders&action=complete&id={$o_id}' class='btn btn-sm btn-primary'><i class='fas fa-box-open'></i>Hoàn Tất</a>";
                        }
                        
                        echo "      </div>
                                </td>
                              </tr>";
                    }
                } else { 
                    echo "<tr><td colspan='4' class='text-center py-4'>Không tìm thấy đơn hàng nào.</td></tr>"; 
                }
                ?>
            </tbody>
        </table>
    </div>
</div>