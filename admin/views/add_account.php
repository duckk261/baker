<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark">Thêm tài khoản mới</h3>
        <a href="index.php?page=accounts" class="btn btn-secondary shadow-sm"><i class="fas fa-arrow-left me-2"></i>Quay lại</a>
    </div>

    <div class="card shadow-sm border-0" style="max-width: 900px;">
        <div class="card-body p-4">
            <form method="POST" action="index.php?page=add_account">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Họ và Tên</label>
                        <input type="text" name="full_name" class="form-control border-primary" placeholder="Nhập họ và tên..." required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tên đăng nhập (Username)</label>
                        <input type="text" name="username" class="form-control border-primary" placeholder="Dùng để đăng nhập..." required>
                    </div>
                </div>

                <div class="row">
                   <div class="col-md-6 mb-3">
    <label class="form-label fw-bold">Mật khẩu</label>
    <div class="input-group">
        <input type="password" name="password" id="password" class="form-control border-primary" placeholder="Nhập mật khẩu..." required>
        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password" style="border-color: #dee2e6;">
            <i class="fas fa-eye-slash text-muted"></i>
        </button>
    </div>
</div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control border-primary" placeholder="Ví dụ: email@gmail.com" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Số điện thoại</label>
                        <input type="text" name="phone_number" class="form-control border-primary" placeholder="Nhập số điện thoại...">
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">Phân quyền (Role)</label>
                        <select name="role" class="form-select border-primary">
                            <option value="User">User (Khách hàng bình thường)</option>
                            <option value="Admin">Admin (Quản trị viên)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Địa chỉ</label>
                    <textarea name="address" class="form-control border-primary" rows="2" placeholder="Nhập địa chỉ nhận hàng/liên hệ..."></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary px-4 fw-bold fs-6"><i class="fas fa-plus me-2"></i>Tạo tài khoản</button>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const toggleBtns = document.querySelectorAll('.toggle-password');
    toggleBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            } else {
                input.type = "password";
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            }
        });
    });
});
</script>