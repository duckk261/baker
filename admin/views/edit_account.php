<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark">Sửa tài khoản #<?php echo $_GET['id']; ?></h3>
        <a href="index.php?page=accounts" class="btn btn-secondary shadow-sm"><i class="fas fa-arrow-left me-2"></i>Quay lại</a>
    </div>

    <div class="card shadow-sm border-0" style="max-width: 900px;">
        <div class="card-body p-4">
            <form method="POST" action="index.php?page=edit_account&id=<?php echo $_GET['id']; ?>">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Họ và Tên</label>
                        <input type="text" name="full_name" class="form-control border-primary" value="<?php echo htmlspecialchars($account['full_name'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tên đăng nhập (Username)</label>
                        <input type="text" name="username" class="form-control border-primary" value="<?php echo htmlspecialchars($account['username'] ?? ''); ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-danger">Đổi mật khẩu (Để trống nếu không đổi)</label>
                        <input type="password" name="password" class="form-control border-danger" placeholder="Nhập mật khẩu mới nếu muốn đổi...">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control border-primary" value="<?php echo htmlspecialchars($account['email'] ?? ''); ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Số điện thoại</label>
                        <input type="text" name="phone_number" class="form-control border-primary" value="<?php echo htmlspecialchars($account['phone_number'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">Phân quyền (Role)</label>
                        <select name="role" class="form-select border-primary">
                            <option value="User" <?php echo (($account['role'] ?? '') == 'User') ? 'selected' : ''; ?>>User (Khách hàng bình thường)</option>
                            <option value="Admin" <?php echo (($account['role'] ?? '') == 'Admin') ? 'selected' : ''; ?>>Admin (Quản trị viên)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Địa chỉ</label>
                    <textarea name="address" class="form-control border-primary" rows="2"><?php echo htmlspecialchars($account['address'] ?? ''); ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-warning px-4 fw-bold fs-6"><i class="fas fa-save me-2"></i>Lưu cập nhật</button>
            </form>
        </div>
    </div>
</div>