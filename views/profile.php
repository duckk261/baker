<?php 
require_once 'app/models/UserModel.php';
$userModel = new UserModel($db);

// Lấy thông tin user hiện tại để điền sẵn vào form
$user_info = $userModel->getUserProfile($_SESSION['account_id']);

include 'header.php'; 
?>

<div class="container-xxl py-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 bg-light rounded p-5">
                <h1 class="display-6 text-center mb-4">My Profile</h1>
                
                <div id="profile-msg" class="alert d-none"></div>
                
                <form id="profileForm">
                    <div class="mb-3">
                        <label class="form-label text-muted">Full Name</label>
                        <input type="text" name="ten_kh" class="form-control" value="<?php echo $user_info['full_name']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Email Address</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $user_info['email']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="<?php echo $user_info['phone_number']; ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted">Shipping Address</label>
                        <textarea name="address" class="form-control" style="height: 100px"><?php echo $user_info['address']; ?></textarea>
                    </div>
                    
                    <button type="button" onclick="updateProfileAJAX()" class="btn btn-primary w-100 py-3">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function updateProfileAJAX() {
    let form = document.getElementById('profileForm');
    let formData = new FormData(form);
    let msgBox = document.getElementById('profile-msg');

    fetch('index.php?page=user&action=update_profile', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        msgBox.classList.remove('d-none', 'alert-danger', 'alert-success');
        
        if (data.status === 'success') {
            msgBox.classList.add('alert-success');
            msgBox.innerText = data.message;
            // Cập nhật thành công thì đợi 1.5s rồi load lại trang để đổi tên trên Header
            setTimeout(() => { location.reload(); }, 1500);
        } else {
            msgBox.classList.add('alert-danger');
            msgBox.innerText = data.message;
        }
    })
    .catch(err => console.error('Lỗi:', err));
}
</script>

<?php include 'footer.php'; ?>