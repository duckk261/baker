<?php 
include 'header.php'; 
include 'db_connect.php'; 

// Kiểm tra đăng nhập
if (!isset($_SESSION['account_id'])) {
    header("Location: login.php");
    exit();
}

$ma_kh = $_SESSION['account_id'];
$sql = "SELECT * FROM khach_hang WHERE Ma_KH = '$ma_kh'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);
?>

<div class="container-xxl py-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="bg-light rounded p-5">
                    <div class="text-center mb-4">
                        <h1 class="display-6">My Profile</h1>
                        <p class="text-primary">Manage your personal information</p>
                    </div>

                    <div id="profile-alert"></div>

                    <form id="profileForm">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold">Full Name</label>
                                <input type="text" name="ten_kh" class="form-control" 
                                       value="<?php echo $user['Ten_KH']; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email Address</label>
                                <input type="email" name="email" class="form-control" 
                                       value="<?php echo $user['Email']; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Phone Number</label>
                                <input type="text" name="phone" class="form-control" 
                                       value="<?php echo $user['Dien_thoai']; ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Address</label>
                                <textarea name="address" class="form-control" style="height: 100px" required><?php echo $user['Dia_chi']; ?></textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary w-100 py-3">Update Profile</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Ngăn chặn F5 trang
    
    let formData = new FormData(this);
    let alertBox = document.getElementById('profile-alert');

    fetch('api/update_profile.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            alertBox.innerHTML = `<div class='alert alert-success'>${data.message}</div>`;
            // Tự động cập nhật tên trên Header mà không cần F5
            const navName = document.querySelector('.dropdown-toggle');
            if(navName) {
                // Giữ lại icon User và chỉ đổi phần chữ
                navName.innerHTML = `<div class="flex-shrink-0 btn-sm-square border border-light rounded-circle me-2"><i class="fa fa-user text-primary"></i></div><span class="text-light small d-none d-lg-inline-block">Hi, ${formData.get('ten_kh')}</span>`;
            }
        } else {
            alertBox.innerHTML = `<div class='alert alert-danger'>${data.message}</div>`;
        }
    })
    .catch(err => console.error('Error:', err));
});
</script>

<?php include 'footer.php'; ?>