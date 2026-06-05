<?php 
require_once 'app/models/UserModel.php';
require_once 'app/classes/Database.php';
$db = Database::getInstance();
$userModel = new UserModel($db);

// Gọi dữ liệu từ Model chuẩn MVC của ông
$user_info = $userModel->getUserProfile($_SESSION['account_id']);

// Tách Họ và Tên ra để nhét vào 2 ô riêng biệt cho đẹp
$full_name = trim($user_info['full_name'] ?? '');
$name_parts = explode(' ', $full_name);
$first_name = array_pop($name_parts); // Lấy chữ cuối cùng làm Tên
$last_name = implode(' ', $name_parts); // Phần còn lại làm Họ Đệm

include 'header.php'; 
?>

<div class="container-xxl py-5">
    <div class="container">
        
        <form id="profileForm">
            <div id="profile-msg" class="alert d-none mb-4"></div>
            
            <div class="row g-5">
                
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                            <h3 class="fw-bold text-dark mb-0" style="font-family: 'Playfair Display', serif;">Chi Tiết Hồ Sơ</h3>
                            <span class="text-success fw-bold"><i class="fas fa-circle me-1 small"></i>Trạng Thái: Hoạt Động</span>
                        </div>

                        <input type="hidden" name="ten_kh" id="hidden_ten_kh" value="<?php echo htmlspecialchars($full_name); ?>">

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold" style="color: #444;">Tên <span class="text-danger">*</span></label>
                                <input type="text" id="input_ten" class="form-control bg-light" value="<?php echo htmlspecialchars($first_name); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold" style="color: #444;">Họ Đệm</label>
                                <input type="text" id="input_ho" class="form-control bg-light" value="<?php echo htmlspecialchars($last_name); ?>" placeholder="Nhập họ đệm...">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold" style="color: #444;">Địa Chỉ Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control bg-light" value="<?php echo htmlspecialchars($user_info['email']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold" style="color: #444;">Số Điện Thoại <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control bg-light" value="<?php echo htmlspecialchars($user_info['phone_number']); ?>" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #444;">Địa Chỉ Giao Hàng <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control bg-light" style="height: 100px" required><?php echo htmlspecialchars($user_info['address']); ?></textarea>
                        </div>

                        <button type="button" onclick="updateProfileAJAX()" class="btn text-white w-100 py-3 fw-bold mt-auto" style="background-color: #c4a16b; border-radius: 4px;">
                            CẬP NHẬT HỒ SƠ
                        </button>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm p-4 h-100">
                        <div class="mb-4 border-bottom pb-3">
                            <h3 class="fw-bold text-dark mb-0" style="font-family: 'Playfair Display', serif;">Cập Nhật Mật Khẩu</h3>
                        </div>
                        
                        <p class="text-muted mb-4 small">(Để trống nếu bạn không muốn đổi mật khẩu)</p>

                       <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #444;">Mật Khẩu Hiện Tại</label>
                            <div class="input-group">
                                <input type="password" name="current_password" id="current_password" class="form-control bg-light" placeholder="Nhập mật khẩu hiện tại...">
                                <button class="btn btn-outline-secondary toggle-password bg-light" type="button" data-target="current_password" style="border-color: #ced4da;">
                                    <i class="fas fa-eye-slash text-muted"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #444;">Mật Khẩu Mới</label>
                            <div class="input-group">
                                <input type="password" name="new_password" id="new_password" class="form-control bg-light" placeholder="Nhập mật khẩu mới...">
                                <button class="btn btn-outline-secondary toggle-password bg-light" type="button" data-target="new_password" style="border-color: #ced4da;">
                                    <i class="fas fa-eye-slash text-muted"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold" style="color: #444;">Xác Nhận Mật Khẩu Mới</label>
                            <div class="input-group">
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control bg-light" placeholder="Nhập lại mật khẩu mới...">
                                <button class="btn btn-outline-secondary toggle-password bg-light" type="button" data-target="confirm_password" style="border-color: #ced4da;">
                                    <i class="fas fa-eye-slash text-muted"></i>
                                </button>
                            </div>
                        </div>

                        <button type="button" onclick="updateProfileAJAX()" class="btn text-white w-100 py-3 fw-bold mt-auto" style="background-color: #c4a16b; border-radius: 4px;">
                            CẬP NHẬT MẬT KHẨU
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
function updateProfileAJAX() {
    // Thuật toán gộp Họ và Tên gửi cho Backend (MVC không bị lỗi)
    let ho = document.getElementById('input_ho').value.trim();
    let ten = document.getElementById('input_ten').value.trim();
    document.getElementById('hidden_ten_kh').value = ho ? (ho + ' ' + ten) : ten;

    // Lấy toàn bộ dữ liệu từ form lớn
    let form = document.getElementById('profileForm');
    let formData = new FormData(form);
    let msgBox = document.getElementById('profile-msg');

    // Chạy AJAX
    fetch('index.php?page=user&action=update_profile', {
        method: 'POST',
        body: formData
    })
    .then(async (response) => {
        const isJson = response.headers.get('content-type')?.includes('application/json');
        const data = isJson ? await response.json() : null;

        if (!response.ok || !data) {
            const errText = await response.text();
            throw new Error(errText || 'Lỗi không xác định từ Server');
        }
        return data;
    })
    .then(data => {
        msgBox.classList.remove('d-none', 'alert-danger', 'alert-success');
        
        if (data.status === 'success') {
            Swal.fire({title: 'Thông báo', text: data.message, confirmButtonColor: '#c4a16b', icon: 'info'}); 
            location.reload(); 
        } else {
            msgBox.classList.add('alert-danger');
            msgBox.innerText = data.message;
            // Cuộn mượt lên chỗ có lỗi cho dễ nhìn
            msgBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    })
    .catch(err => {
        console.error('Chi tiết lỗi:', err);
        Swal.fire({title: 'Thông báo', text: 'Server trả về lỗi lạ (ấn F12 sang tab Console để xem chi tiết nhé)!', confirmButtonColor: '#c4a16b', icon: 'info'});
    });
}
// Thêm đoạn này vào bên dưới hàm updateProfileAJAX()
document.addEventListener("DOMContentLoaded", function() {
    const toggleBtns = document.querySelectorAll('.toggle-password');

    toggleBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Lấy ID của ô input đang được nhắm tới
            const targetId = this.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);
            const icon = this.querySelector('i');

            // Đảo trạng thái (type text <-> password)
            if (passwordInput.type === "password") {
                passwordInput.type = "text"; 
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                passwordInput.type = "password"; 
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });
    });
});
</script>

<?php include 'footer.php'; ?>