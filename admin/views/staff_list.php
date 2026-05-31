<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">Staff management <span class="badge bg-danger fs-6 ms-2"></span></h2>
</div>
<div class="card shadow-sm border-0 p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-dark"><tr><th>ID Tài khoản</th><th>Tên đăng nhập</th><th>Quyền hạn (Role)</th></tr></thead>
            <tbody>
                <?php
                if ($staff_list && mysqli_num_rows($staff_list) > 0) {
                    while ($row = mysqli_fetch_assoc($staff_list)) {
                        $acc_id = $row['account_id'] ?? $row['id'] ?? 'N/A';
                        $username = $row['username'] ?? $row['tai_khoan'] ?? 'N/A';
                        $role = $row['role'] ?? 'staff';
                        
                        $badge = (strtolower($role) == 'admin') 
                            ? "<span class='badge bg-danger px-3 py-2 fs-6 shadow-sm'><i class='fas fa-user-shield me-2'></i>Quản trị viên (Admin)</span>" 
                            : "<span class='badge bg-primary px-3 py-2 fs-6 shadow-sm'><i class='fas fa-user-tie me-2'></i>Nhân viên (Staff)</span>";
                        
                        echo "<tr><td class='fw-bold text-secondary'>#{$acc_id}</td><td class='fw-bold text-dark fs-5'>{$username}</td><td>{$badge}</td></tr>";
                    }
                } else { echo "<tr><td colspan='3' class='text-center py-4 text-muted'>Chưa có dữ liệu nhân sự.</td></tr>"; }
                ?>
            </tbody>
        </table>
    </div>
</div>