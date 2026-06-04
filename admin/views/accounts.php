<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark">Accounts Management</h3>
        <a href="index.php?page=add_account" class="btn btn-primary shadow-sm"><i class="fas fa-plus me-2"></i>Add Account</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3">ID</th>
                            <th class="py-3">Full Name</th>
                            <th class="py-3">Email</th>
                            <th class="py-3">Role</th>
                            <th class="py-3">Registration Date</th>
                            <th class="py-3 text-center">Actions</th> </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($accounts_data)): 
                            $role_color = ['Admin' => 'bg-danger', 'staff' => 'bg-warning text-dark', 'User' => 'bg-info text-white'];
                            $badge_class = $role_color[$row['role']] ?? 'bg-secondary';
                        ?>
                        <tr>
                            <td class="ps-4 text-muted fw-bold">#<?php echo $row['customer_id']; ?></td>
                            <td class="fw-bold text-dark"><?php echo htmlspecialchars($row['username']); ?></td>
                            <td class="text-muted"><?php echo $row['email'] ?? '<em class="small text-danger">Not updated</em>'; ?></td>
                            <td>
                                <span class="badge <?php echo $badge_class; ?> px-3 py-2 rounded-pill shadow-sm">
                                    <?php echo ucfirst($row['role']); ?>
                                </span>
                            </td>
                            <td class="text-secondary"><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                            <td class="text-center">
                                <a href="index.php?page=edit_account&id=<?php echo $row['customer_id']; ?>" class="btn btn-sm btn-outline-warning me-1"><i class="fas fa-edit"></i></a>
                                <a href="index.php?page=accounts&action=delete&id=<?php echo $row['customer_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn chắc chắn muốn xóa tài khoản này?');"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>