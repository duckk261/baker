<div class="container-fluid py-4">
    <!-- Tiêu đề trang -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark">Quản lý Tài Khoản</h3>
        <span class="text-muted small"><?php echo date('d/m/Y'); ?></span>
    </div>

    <!-- Bảng dữ liệu -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3">ID</th>
                            <th class="py-3">Họ Tên</th>
                            <th class="py-3">Email</th>
                            <th class="py-3">Role</th>
                            <th class="py-3 pe-4">Ngày Đăng Ký</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($accounts_data)): 
                            // Định nghĩa màu cho từng loại role
                            $role_color = [
                                'Admin' => 'bg-danger',
                                'staff' => 'bg-warning text-dark',
                                'User'  => 'bg-info text-white'
                            ];
                            $badge_class = $role_color[$row['role']] ?? 'bg-secondary';
                        ?>
                        <tr>
                            <td class="ps-4 text-muted fw-bold">#<?php echo $row['customer_id']; ?></td>
                            <td class="fw-bold text-dark"><?php echo htmlspecialchars($row['username']); ?></td>
                            <td class="text-muted"><?php echo $row['email'] ?? '<em class="small text-danger">Chưa cập nhật</em>'; ?></td>
                            <td>
                                <span class="badge <?php echo $badge_class; ?> px-3 py-2 rounded-pill shadow-sm">
                                    <?php echo ucfirst($row['role']); ?>
                                </span>
                            </td>
                            <td class="pe-4 text-secondary"><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>